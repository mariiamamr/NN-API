<?php

use Illuminate\Database\Seeder;
use App\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Permission::insert(array(
            array('title' => 'Create New Admin', 'name' => 'admin-create', 'label' => 'admin'),
            array('title' => 'Edit Admin Info', 'name' => 'admin-edit', 'label' => 'admin'),
            array('title' => 'View Admin Info', 'name' => 'admin-show', 'label' => 'admin'),
            array('title' => 'Delete Admin Account', 'name' => 'admin-delete', 'label' => 'admin'),
            array('title' => 'Create New Blog', 'name' => 'blog-create', 'label' => 'blog'),
            array('title' => 'Delete Blog', 'name' => 'blog-delete', 'label' => 'blog'),
            array('title' => 'Edit Blog', 'name' => 'blog-edit', 'label' => 'blog'),
            array('title' => 'View Blog', 'name' => 'blog-show', 'label' => 'blog'),
            array('title' => 'Create Education System', 'name' => 'edusystem-create', 'label' => 'edusystem'),
            array('title' => 'Edit Education System', 'name' => 'edusystem-edit', 'label' => 'edusystem'),
            array('title' => 'Delete Education System', 'name' => 'edusystem-delete', 'label' => 'edusystem'),
            array('title' => 'View Education System', 'name' => 'edusystem-show', 'label' => 'edusystem'),
            array('title' => 'Create Grad', 'name' => 'grade-create', 'label' => 'grade'),
            array('title' => 'Edit Grad', 'name' => 'grade-edit', 'label' => 'grade'),
            array('title' => 'View Grad', 'name' => 'grade-show', 'label' => 'grade'),
            array('title' => 'Delete Grad', 'name' => 'grade-delete', 'label' => 'grade'),
            array('title' => 'Create Language', 'name' => 'language-create', 'label' => 'language'),
            array('title' => 'Edit Language', 'name' => 'language-edit', 'label' => 'language'),
            array('title' => 'View Language', 'name' => 'language-show', 'label' => 'language'),
            array('title' => 'Delete Language', 'name' => 'language-delete', 'label' => 'language'),
            array('title' => 'Create Subject', 'name' => 'subject-create', 'label' => 'subject'),
            array('title' => 'Edit Subject', 'name' => 'subject-edit', 'label' => 'subject'),
            array('title' => 'Delete Subject', 'name' => 'subject-delete', 'label' => 'subject'),
            array('title' => 'View Subject', 'name' => 'subject-show', 'label' => 'subject'),
            array('title' => 'Create University Degree Tags', 'name' => 'uni_degree-create', 'label' => 'uni_degree'),
            array('title' => 'Edit  University Degree Tags', 'name' => 'uni_degree-edit', 'label' => 'uni_degree'),
            array('title' => 'Delete University Degree Tags', 'name' => 'uni_degree-delete', 'label' => 'uni_degree'),
            array('title' => 'View University Degree Tags', 'name' => 'uni_degree-show', 'label' => 'uni_degree'),
            array('title' => 'Create Roles', 'name' => 'privilege-create', 'label' => 'privilege'),
            array('title' => 'Edit Roles', 'name' => 'privilege-edit', 'label' => 'privilege'),
            array('title' => 'Delete Roles', 'name' => 'privilege-delete', 'label' => 'privilege'),
            array('title' => 'View Roles', 'name' => 'privilege-show', 'label' => 'privilege'),
            array('title' => 'Create Permission', 'name' => 'permission-create', 'label' => 'permission'),
            array('title' => 'Edit Permission', 'name' => 'permission-edit', 'label' => 'permission'),
            array('title' => 'Delete Permission', 'name' => 'permission-delete', 'label' => 'permission'),
            array('title' => 'View Permission', 'name' => 'permission-show', 'label' => 'permission'),
            array('title' => 'View Teachers', 'name' => 'teacher-show', 'label' => 'teacher'),
            array('title' => 'Add Price Range For Teachers', 'name' => 'price-index', 'label' => 'price'),
            array('title' => 'Add Basic Site Info', 'name' => 'config-index', 'label' => 'config'),
            array('title' => 'View Promo Codes', 'name' => 'promo-index', 'label' => 'promo'),
            array('title' => 'Create Promo Codes', 'name' => 'promo-create', 'label' => 'promo'),
            array('title' => 'Delete Promo Codes', 'name' => 'promo-delete', 'label' => 'promo'),
            array('title' => 'Edit Promo Codes', 'name' => 'promo-edit', 'label' => 'promo'),
            array('title' => 'Teacher account approval', 'name' => 'teacher-approval', 'label' => 'teacher'),
            array('title' => 'Withdraw approval', 'name' => 'teacher-withdraw_approval', 'label' => 'teacher'),
            array('title' => 'Teacher Price approval', 'name' => 'teacher-price_approval', 'label' => 'teacher'),
            array('title' => 'View FAQ', 'name' => 'faq-index', 'label' => 'faq'),
            array('title' => 'Create FAQ', 'name' => 'faq-create', 'label' => 'faq'),
            array('title' => 'Edit FAQ', 'name' => 'faq-edit', 'label' => 'faq'),
            array('title' => 'Delete FAQ', 'name' => 'faq-delete', 'label' => 'faq')
        ));
    }
}
