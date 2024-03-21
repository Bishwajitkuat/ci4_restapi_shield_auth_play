<?php

namespace App\Controllers\Api;

use CodeIgniter\RESTful\ResourceController;

class ProjectController extends ResourceController
{
    // post
    public function addProject()
    {
        // insert project to db
        // token to identify user
    }

    // get
    public function getProjects()
    {
        // returns project belongs to this user
        // token to identify user
    }

    // delete
    public function deleteProject($id)
    {
        // delete project
        // token to  user
    }
}
