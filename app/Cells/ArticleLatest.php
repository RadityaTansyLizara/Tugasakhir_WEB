<?php
namespace App\Cells;
use App\Models\ArticleModel;

class ArticleLatest
{
    public function render($category = null)
    {
        $model = new ArticleModel();

        if ($category) {
            $article = $model
                ->where("category", $category)
                ->orderBy("created_at", "DESC")
                ->limit(5)
                ->findAll();
        } else {
            $article = $model
                ->orderBy("created_at", "DESC")
                ->limit(5)
                ->findAll();
        }

        return view("components/article_latest", [
            "article" => $article,
            "category" => is_array($category) ? implode(", ", $category) : $category,
        ]);
    }
}
