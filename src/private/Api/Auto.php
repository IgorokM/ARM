<?php declare(strict_types=1);

namespace Api;

use \PDO;
use \Exception;

class Auto extends Api
{

    /**
     * Method GET
     * http://domen/auto
     */
    public function indexAction(): string
    {
        $db = new Db();
        $auto = $db->query('SELECT `marka`, `model` FROM `auto`')->fetchAll();
        $db = null;
        if ($auto) {
            return $this->response($auto, 200);
        }
        return $this->response('Data not found', 404);
    }

    /**
     * Method GET
     * http://domen/auto/{id}
     */
    public function viewAction(): string
    {
        $id = $this->GetParams;
        if ($id) {
            $db = new Db();
            $id = (int)$id;
            $sql = 'SELECT `id`, `name` FROM `car_part` WHERE id = ?';
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
     * http://domen/auto +params
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
     * http://domen/auto/1 +params
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
     * http://domen/auto/1
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
