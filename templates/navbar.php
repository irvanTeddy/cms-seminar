<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">

    <div class="container-fluid">

        <a class="navbar-brand fw-bold" href="#">
            CMS Seminar Informatika
        </a>

        <div class="ms-auto text-white">

            <?php
            if (isset($_SESSION['nama'])) {
                echo "Halo, <b>" . $_SESSION['nama'] . "</b>";
            }
            ?>

        </div>

    </div>

</nav>