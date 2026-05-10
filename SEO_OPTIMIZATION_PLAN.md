# Sri Shringarr SEO Optimization Suite: Implementation Plan

## 1. Requirements

### Core Objective
To provide a centralized, user-friendly interface in the Nexus Admin Suite to manage SEO metadata and provide real-time optimization feedback similar to the All-in-One SEO (AIOSEO) plugin in WordPress.

### Functional Requirements
- **Metadata Management**: CRUD operations for `meta_title`, `meta_description`, and `meta_keywords` for all products, categories, and static pages.
- **Focus Keyword Analysis**: Ability to set a "Focus Keyword" per entity to drive the optimization score.
- **Real-time SEO Scorer**: A visual indicator (0-100%) that updates as the user edits fields.
- **Optimization Checklist**: Actionable tips (e.g., "Title is too short", "Keyword missing in description") to improve the score.
- **Google Snippet Preview**: Real-time visualization of how the page will appear in search results.
- **Dynamic Frontend Injection**: Automatic injection of metadata into the root website's `<head>`.

---

## 2. Design & Architecture

### 2.1 Database Schema Updates
We will expand existing tables to include focus keyword and score tracking.

- **New Columns** to be added to `product`, `garment_product`, `jewel_subcat`, `garments`, and `pages`:
  - `seo_focus_keyword` (VARCHAR 255)
  - `seo_score` (INT 0-100)

### 2.2 Backend API (PHP)
- **`SeoModel.php`**: Handles database persistence for SEO fields.
- **`SeoController.php`**: Exposes endpoints:
  - `GET /api/v1/seo/stats`: Get overall site SEO health.
  - `POST /api/v1/seo/update`: Save SEO metadata.

### 2.3 Frontend SEO Engine (JavaScript)
A dedicated `SeoAnalyzer.js` module will run in the admin panel to calculate scores based on:
1. **Title Length**: 50-60 characters.
2. **Description Length**: 120-160 characters.
3. **Keyword Density**: Presence in Title and Description.
4. **Content Quality**: Presence in description text.

### 2.4 Admin UI Components
- **SEO Widget**: A side-panel or tab in the Product/Category editor.
- **Score Gauge**: A circular progress bar indicating health.
- **Checklist**: A list of pass/fail/warning items.

---

## 3. Implementation Tasks

### Phase 1: Foundation & Database
- [ ] **Task 1.1**: Run SQL migrations to add `seo_focus_keyword` and `seo_score` to all relevant tables.
- [ ] **Task 1.2**: Populate the `pages` table with common site URLs (About Us, Contact, FAQ).

### Phase 2: API & Models
- [ ] **Task 2.1**: Update `ProductModel` and `CategoryModel` to include SEO fields in their fetch/update methods.
- [ ] **Task 2.2**: Create a standalone `SeoController` for bulk SEO operations.

### Phase 3: Real-time Analysis Engine
- [ ] **Task 3.1**: Develop `SeoAnalyzer.js` with rules for length, keyword placement, and density.
- [ ] **Task 3.2**: Create a \"Google Search Preview\" component in CSS/JS.

### Phase 4: Admin UI Integration
- [ ] **Task 4.1**: Create `admin/project/pages/seo_manager.php` for site-wide SEO control.
- [ ] **Task 4.2**: Integrate the SEO Widget into the `product_edit.php` and `categories.php` views.

### Phase 5: Root Website Integration
- [ ] **Task 5.1**: Update `sri/functions.php` with a `get_seo_data()` helper.
- [ ] **Task 5.2**: Update `sri/header.php` to use the dynamic helper for title and meta tags.

---
