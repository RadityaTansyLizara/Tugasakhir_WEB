<?php
namespace App\Controllers;
use CodeIgniter\RESTful\ResourceController;
use App\Models\ArticleModel;

class ArticleApi extends ResourceController
{
    protected $modelName = ArticleModel::class;
    protected $format    = 'json';

    // GET /api/articles
    public function index()
    {
        $data = $this->model->findAll();
        return $this->respond($data);
    }

    // GET /api/articles/{id}
    public function show($id = null)
    {
        $data = $this->model->find($id);
        if ($data) {
            return $this->respond($data);
        }
        return $this->failNotFound('Article not found');
    }

    // POST /api/articles
    public function create()
    {
        $data = $this->request->getJSON(true);
        if ($this->model->insert($data)) {
            $data['id'] = $this->model->getInsertID();
            return $this->respondCreated($data);
        }
        return $this->failValidationErrors($this->model->errors());
    }

    // PUT /api/articles/{id}
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        if ($this->model->update($id, $data)) {
            $data['id'] = $id;
            return $this->respond($data);
        }
        return $this->failValidationErrors($this->model->errors());
    }

    // DELETE /api/articles/{id}
    public function delete($id = null)
    {
        if ($this->model->delete($id)) {
            return $this->respondDeleted(['id' => $id]);
        }
        return $this->failNotFound('Article not found or already deleted');
    }
} 