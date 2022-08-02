<!DOCTYPE html>
<html lang="en">

<head>
    <!-- header start -->
    <?= $this->include('Template/Header') ?>
    <?= $this->renderSection('sectionheader') ?>
    <!-- header end -->
</head>

<body>
    <!-- navbar start -->
    <?= $this->include('Template/Navbar') ?>
    <?= $this->renderSection('sectionnavbar') ?>
    <!-- navbar end -->

    <!-- content start -->
    <?= $this->renderSection('sectioncontent') ?>
    <!-- content end -->

    <!-- footer start -->
    <?= $this->include('Template/Footer') ?>
    <!-- footer end -->

    <!-- js start -->
    <?= $this->include('Template/Js') ?>
    <?= $this->renderSection('sectionjs') ?>
    <!-- js end -->
</body>

</html>