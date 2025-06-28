<?php
namespace App\Models;
use CodeIgniter\Model;
class ArticleModel extends Model
{
    protected $table = 'article';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $allowedFields = ['title', 'content', 'image', 'status', 'slug', 'created_at', 'updated_at', 'category'];
    protected $useTimestamps = true;

    public function searchArticles($keyword)
    {
        return $this->like('title', $keyword)
                    ->orLike('content', $keyword)
                    ->findAll();
    }

    public function getCategories()
    {
        return $this->select('category')
            ->distinct()
            ->where('category IS NOT NULL')
            ->where('category !=', '')
            ->findAll();
    }
}
