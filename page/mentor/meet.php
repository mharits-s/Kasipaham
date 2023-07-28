<?php

use Config\Database;
use Config\Session;
use Controllers\CourseController;
use Validation\Validation;

require_once(__DIR__ . "../../../config/config.php");
middleware(["auth", "mentor"]);
require_once('layouts/template.php');

require_once(__DIR__ . "../../../config/config.php");
$id = $_GET['id'];
$data1 = Database::getFirst("
SELECT course.*,
       COUNT(learning_materials.id) AS number_of_meetings,
       COUNT(DISTINCT courses_taken.course_id) AS taken,
       MAX(learning_materials.created_at) AS last_material
FROM course
LEFT JOIN learning_materials ON course.id = learning_materials.course_id
LEFT JOIN courses_taken ON course.id = courses_taken.course_id
WHERE course.id = '$id'
GROUP BY course.id;
");
$data2 = Database::getFirst("
SELECT learning_materials.*, course.course_title
FROM learning_materials
LEFT JOIN course ON learning_materials.course_id = course.id
WHERE learning_materials.id = '$id';
");

?>


<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Kasipaham <?= Session::auth()['name'] ?></title>
    <?php require($template['css']) ?>
</head>

<body>

    <div id="page-container" class="sidebar-o sidebar-dark enable-page-overlay side-scroll page-header-fixed main-content-narrow">
        <!-- Side Overlay-->
        <?php require($template['sidebar']) ?>

        <!-- END Sidebar -->

        <!-- Header -->
        <?php require($template['header']) ?>
        <!-- END Header -->

        <!-- Main Container -->
        <main id="main-container">
            <!-- END Hero Content -->

            <!-- Navigation -->
            <div class="bg-body-extra-light">
                <div class="content content-boxed py-3">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb breadcrumb-alt">
                            <li class="breadcrumb-item">
                                <a class="link-fx" href="<?= url('/mentor/dashboard') ?>">Mentor</a>
                            </li>
                            <li class="breadcrumb-item">
                                <a class="link-fx" href="<?= url('/mentor/course') ?>">Courses</a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                            <a class="link-fx" href="<?= url('/mentor/detail-course?id=' . $data2['course_id']) ?>"><?= $data2['course_title'] ?></a>
                            </li>
                            <li class="breadcrumb-item" aria-current="page">
                                <?= $data2['title'] ?>
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
            <!-- END Navigation -->

            <!-- Page Content -->
            <div class="content content-boxed">

                <!-- navbar -->
                <!-- navbar -->

                <!-- Lessons -->
                <div class="block block-rounded">
                    <div class="container-fluid ratio ratio-16x9 fs-sm p-5 justify-content-center" id="containerDetailMeet">
                    <?= $data2['embed_video'] ?>
                    </div>
                    <div role="separator" class="dropdown-divider m-0"></div>
                    <div class="container bg-body-extra-light px-5 py-3">
                    <?= $data2['description'] ?>
                    </div>
                </div>
                <!-- END Lessons -->
            </div>
            <!-- END Page Content -->
        </main>
        <!-- END Main Container -->


        <?php require($template['footer']) ?>
    </div>
    <!-- END Page Container -->





    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
    <?php require($template['js']) ?>
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>


    <script>
        tinymce.init({
            selector: 'textarea'
        });
    </script>
    <?php include($notif) ?>
    
</body>

</html>