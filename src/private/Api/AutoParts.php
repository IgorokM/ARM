<?php declare(strict_types=1);

namespace Api;

use \PDO;
use \Exception;

class AutoParts extends Api
{
    /**
     * Method GET
     * http://domen/parts
     */
    public function indexAction(): string
    {
        try {
            $db = new Db();
            $parts = $db->query('SELECT `id`, `name`, `img`, `width`, `height`, `weight` FROM `car_part`')->fetchAll();
            $db = null;
            if ($parts) {
                return $this->response($parts, 200);
            }
            return $this->response('Data not found', 404, true);
        } catch (Exception $e) {
            return $this->response($e->getMessage(), 500, true);
        }
    }

    /**
     * Method GET
     * http://domen/parts/{id}
     */
    public function viewAction(): string
    {
        $id = $this->getParams;
        if ($id) {
            $db = new Db();
            $id = (int)$id;
            $sql = 'SELECT `id`, `name`, img, width FROM `car_part` WHERE id = ?';
            $q = $db->prepare($sql);
            $db = null;
            $q->execute([$id]);
            if ($id) {
                return $this->response($q->fetch(), 200);
            }
        }
        return $this->response('Data not found', 404);
    }

    /**
     * Method POST
     * http://domen/parts +params
     */
    public function createAction():string
    {
        if ($this->bodyPost) {
            $db = new Db();
            try {
                $db->beginTransaction();
                $sql = 'INSERT INTO `car_part`
                            (`name`, `width`, `height`, `weight`, `id_auto`)
                        VALUES 
                            (:name, :width, :height, :weight, :id_auto)';
                $q = $db->prepare($sql);
                $q->execute($this->bodyPost);
                $db->commit();
                $id = $db->lastInsertId();
                $db = null;
                return $this->response(['id' => $id,'dataSaved' => true], 200);
            } catch (PDOExecption $e) {
                $db->rollback();
                $db = null;
                return $this->response("Saving error", 500);
            }
        }
        return $this->response("Saving error", 500);
    }

    /**
     * Метод PUT
     * http://domen/parts/1 +params
     * @return string
     */
    public function updateAction()
    {
        $parse_url = parse_url($this->requestUri[0]);
        $userId = $parse_url['path'] ?? null;

        $db = (new Db())->getConnect();

        if (!$userId || !Users::getById($db, $userId)) {
            return $this->response("User with id=$userId not found", 404);
        }

        $name = $this->requestParams['name'] ?? '';
        $email = $this->requestParams['email'] ?? '';

        if ($name && $email) {
            if ($user = Users::update($db, $userId, $name, $email)) {
                return $this->response('Data updated.', 200);
            }
        }
        return $this->response("Update error", 400);
    }

    /**
     * Метод DELETE
     * http://domen/parts/1
     * @return string
     */
    public function deleteAction()
    {
        $parse_url = parse_url($this->requestUri[0]);
        $userId = $parse_url['path'] ?? null;

        $db = (new Db())->getConnect();

        if (!$userId || !Users::getById($db, $userId)) {
            return $this->response("User with id=$userId not found", 404);
        }
        if (Users::deleteById($db, $userId)) {
            return $this->response('Data deleted.', 200);
        }
        return $this->response("Delete error", 500);
    }
}
