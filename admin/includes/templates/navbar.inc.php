<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php"><?=lang('Home_Admin')?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#app-nav" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="app-nav">
            <ul class="navbar-nav navbar-links me-auto mb-2 mb-lg-0">
                <li class="nav-item active">
                    <a class="nav-link active" aria-current="page" href="categories.php"><?=lang('Categories')?></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link active" aria-current="page" href="items.php"><?=lang('ITEMS')?></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link active" aria-current="page" href="members.php"><?=lang('MEMBERS')?></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link active" aria-current="page" href="comments.php"><?=lang('COMMENTS')?></a>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right ml-auto"> <!-- go to left ml-auto -->
                <li class="dropdown">
                    <a class="dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-expanded="false">
                        More Option
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../index.php"><?=lang('Visit_Shop')?></a></li>
                        <li><a class="dropdown-item" href="./members.php?do=Edit&UserID=<?=$_SESSION['ID']?>"><?=lang('Edit_Profile')?></a></li>
                        <li><a class="dropdown-item" href="logout.php/"><?=lang('Logout')?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
