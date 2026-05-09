<?php

namespace Api\V1\Models;

class CategoryModel extends BaseModel {
    
    public function getNestedCategories() {
        $sql = "SELECT parent_type, parent_id, parent_name, child_id, child_name 
                FROM category_mappings 
                ORDER BY parent_type, parent_name, child_name";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $result = [
            'apparel' => [],
            'jewellery' => []
        ];

        $temp = [
            'apparel' => [],
            'jewellery' => []
        ];

        foreach ($rows as $row) {
            $type = $row['parent_type'];
            $pName = $row['parent_name'];
            $pId = $row['parent_id'];

            if (!isset($temp[$type][$pName])) {
                $temp[$type][$pName] = [
                    'id' => $pId,
                    'name' => $pName,
                    'subcategories' => []
                ];
            }

            if (!empty($row['child_name'])) {
                $temp[$type][$pName]['subcategories'][] = [
                    'id' => $row['child_id'],
                    'name' => $row['child_name']
                ];
            }
        }

        foreach ($temp as $type => $mains) {
            foreach ($mains as $main) {
                $result[$type][] = $main;
            }
        }

        return $result;
    }
}
