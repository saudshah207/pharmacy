<aside id="sidebar" class="d-flex flex-column offcanvas-xl offcanvas-start col-xl-2 col-12 p-3" tabindex="-1">
    <h2 class="mb-4">
        <a href="/employee" class="sidebar-link main-link">Dashboard</a>
    </h2>

    <ul class="list-unstyled">
        <li class="pb-3">
            <a href="/employee/pos" class="sidebar-link d-flex align-items-center gap-2">
                <img src="/assets/icons/point-of-sale.svg" alt="">
                Point of Sale
            </a>
        </li>
        <li class="pb-3">
            <a href="/employee/sales" class="sidebar-link d-flex align-items-center gap-2">
                <img src="/assets/icons/history.svg" alt="">
                See Past Sales
            </a>
        </li>
    </ul>

    <form class="mt-auto" action="/logout" method="post">
        <button class="btn btn-secondary w-100" type="submit" name="logout">Log Out</button>
    </form>
</aside>