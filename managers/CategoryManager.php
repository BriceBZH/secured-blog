<?php
/**
 * @author : Gaellan
 * @link : https://github.com/Gaellan
 */


class CategoryManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    public function findAll() : array {
        $query = $this->db->prepare('SELECT * FROM categories');
        $query->execute();
        $categDB = $query->fetchAll(PDO::FETCH_ASSOC);
        $categories = [];

        foreach($categDB as $categ) {
            $category = new Category($categ["description"]);
            $category->setId($categ["id"]);
            $categories[] = $category;
        }

        return $categories;
    }


    public function findOne(int $id) : ?Category {
        $query = $this->db->prepare('SELECT * FROM categories WHERE id = :id');
        $parameters = [
            "id" => $id,
        ];
        $query->execute($parameters);
        $categ = $query->fetch(PDO::FETCH_ASSOC);

        if($categ) {
            $category = new Category($categ["description"]);
            $category->setId($categ["id"]);

            return $category;
        }

        return null;
    }
}