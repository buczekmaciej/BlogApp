<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Tag;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ArticleServices
{
    public function __construct(private readonly Session $session)
    {
    }

    public function syncLikes(Article $article): void
    {
        if ($article->likes()->get()->contains(auth()->user())) {
            $article->likes()->detach(auth()->user());
        } else {
            $article->likes()->sync(auth()->user());
        }
    }

    public function syncBookmarks(Article $article): void
    {
        if (auth()->user()->bookmarks()->get()->contains($article)) {
            auth()->user()->bookmarks()->detach($article);
        } else {
            auth()->user()->bookmarks()->sync($article);
        }
    }

    public function titleToSlug(string $title)
    {
        return implode("-", explode(' ', strtolower($title)));
    }

    public function handleFilesUpload(Request $request, string $session, string $path, string $route, array $params = []): RedirectResponse
    {
        $sizeOfFiles = sizeof($request->files->all('files'));

        $valid = $sizeOfFiles > 0 ? $request->validate(
            [
                'files' => 'required',
                'files.*' => 'file|max:5000|mimes:jpeg,jpg,png'
            ]
        ) : $this->session->has($session);

        if ($valid) {
            if ($this->session->has($session) && $sizeOfFiles === 0) {
                return redirect()->route($route, $params);
            }

            $uploads = [];
            $path = 'assets/' . $path;
            $isTemp = !str_contains($path, 'images/');
            foreach ($request->files->all('files') as $img) {
                $now = Carbon::now()->format('U');
                $rand = Str::random($isTemp ? 20 : 40);
                $extension = $img->getClientOriginalExtension();
                $newName = !$isTemp ? "{$rand}.{$extension}" : "temp_{$now}{$rand}.{$extension}";

                try {
                    $img->move($path, $newName);

                    $uploads[] = $newName;
                } catch (Exception $e) {
                    return back()->withErrors($e->getMessage());
                }
            }
            $this->session->put($session, array_merge($this->session->get($session) ?? [], $uploads));

            return redirect()->route($route, $params);
        }

        return back();
    }

    public function deleteImage(string $file, string $asset, string $session): RedirectResponse
    {
        if (File::exists(public_path("assets/{$asset}/{$file}"))) {
            try {
                File::delete(public_path("assets/{$asset}/{$file}"));

                $files = $this->session->get($session);
                unset($files[array_search($file, $files)]);

                $this->session->put($session, $files);
            } catch (Exception $e) {
                return back()->withErrors($e->getMessage());
            }
        }

        return back();
    }

    public function createArticle(Request $request): RedirectResponse
    {
        $valid = $request->validate([
            'content' => 'required|string',
            'thumbnail' => 'required',
            'title' => 'string|required',
            'tags' => 'array|required'
        ]);

        if ($valid) {
            $articleData = [
                'title' => $valid['title'],
                'slug' => $this->titleToSlug($valid['title']),
                'content' => $valid['content'],
            ];

            $article = new Article($articleData);
            $tags = Tag::whereIn('name', $valid['tags'])->get();
            $article->author()->associate(auth()->user());
            $article->save();
            $article->tags()->saveMany($tags);
            $embeds = [];

            $content = $valid['content'];

            foreach ($this->session->get('uploaded') as $img) {
                $rand = Str::random(40);
                $extension = explode(".", $img)[1];
                $newName = "{$rand}.{$extension}";

                $articleFolderPath = public_path('assets/images/' . $article->uuid);
                if (!File::isDirectory($articleFolderPath)) {
                    File::makeDirectory($articleFolderPath, 0777, true, true);
                }

                File::move(public_path('assets/uploads/' . $img), $articleFolderPath . '/' . $newName);

                $embeds[] = $newName;
                if (str_contains($content, $img)) {
                    str_replace($img, "/assets/images/" . $article->uuid . '/' . $newName, $content);
                }

                if ($valid['thumbnail'] === $img) {
                    $article->thumbnail = $newName;
                }
            }

            $article->embeds = $embeds;
            $article->content = $content;

            if ($article->save()) {
                $this->session->forget('uploaded');

                return redirect()->route('articles.view', $article->slug);
            }
        }

        return back()->with('title', 'content');
    }

    public function updateArticle(Request $request, Article $article, string $route): RedirectResponse
    {
        $valid = $request->validate([
            'content' => 'required|string',
            'thumbnail' => 'required',
            'title' => 'string|required',
            'tags' => 'array|required'
        ]);

        if ($valid) {
            $data = [
                'thumbnail' => $valid['thumbnail'],
                'title' => $valid['title'],
                'embeds' => $this->session->get('existing')
            ];
            $content = $valid['content'];
            foreach ($this->session->get('existing') as $img) {
                if (str_contains($content, $img) && !str_contains($content, "/assets/images/{$article->uuid}/$img")) {
                    str_replace($img, "/assets/images/" . $article->uuid . '/' . $img, $content);
                }
            }
            $data['content'] = $content;

            $article->tags()->detach();
            $names = implode("', '", $valid['tags']);
            $tags = Tag::whereIn('name', $valid['tags'])->orderByRaw("FIELD(`name`, '{$names}')")->get();
            $article->tags()->saveMany($tags);

            if ($article->title !== $valid['title']) {
                $data['slug'] = $this->titleToSlug($valid['title']);
            }

            $article->update($data);

            return redirect()->route($route, $article->slug);
        }

        return back()->with('content', 'title');
    }

    public function getUnusedTags(Collection $tags): Collection
    {
        return Tag::whereNotIn('name', $tags)->orderBy('name', 'ASC')->pluck('name');
    }
}
