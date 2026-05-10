<?php

namespace Api\V1\Controllers;

use Api\V1\Core\Controller;
use Api\V1\Core\Response;
use Api\V1\Models\SeoModel;

class SeoController extends Controller {
    
    public function get() {
        $pageType = $_GET['type'] ?? 'page';
        $entityId = isset($_GET['id']) ? (int)$_GET['id'] : null;
        $urlSlug = $_GET['slug'] ?? null;
        
        $model = new SeoModel();
        
        if ($entityId === null && $urlSlug === null) {
            $data = $model->getAllMetadata($pageType);
            Response::success("SEO list retrieved", $data);
            return;
        }

        if ($pageType === 'page' && $urlSlug === null && $entityId !== null) {
            $data = $model->getMetadataById($entityId);
        } else {
            $data = $model->getMetadata($pageType, $entityId, $urlSlug);
        }
        
        if (!$data) {
            // Return empty structure if not found
            $data = [
                'meta_title' => '',
                'meta_description' => '',
                'meta_keywords' => '',
                'focus_keyword' => '',
                'seo_score' => 0
            ];
        }
        
        Response::success("SEO data retrieved", $data);
    }

    public function update() {
        $data = $this->getRequestData();
        
        if (!isset($data['page_type'])) {
            Response::error("Missing page_type", 400);
        }
        
        $model = new SeoModel();
        if ($model->updateMetadata($data)) {
            Response::success("SEO updated successfully");
        } else {
            Response::error("Failed to update SEO");
        }
    }

    public function getSettings() {
        $model = new SeoModel();
        $settings = $model->getSettings();
        Response::success("SEO settings retrieved", $settings);
    }

    public function updateSettings() {
        $settings = $this->getRequestData();
        $model = new SeoModel();
        if ($model->updateSettings($settings)) {
            Response::success("SEO settings updated");
        } else {
            Response::error("Failed to update settings");
        }
    }
}
