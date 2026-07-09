<aside id="sidebar" class="d-flex flex-column offcanvas-xl offcanvas-start col-xl-2 col-12 p-3" tabindex="-1">
    <h2 class="mb-4">
        <a href="/admin" class="sidebar-link main-link">Dashboard</a>
    </h2>

    <ul class="list-unstyled">
        <li class="pb-3">
            <a href="/admin/sales" class="sidebar-link d-flex align-items-center gap-2">
                <img src="/assets/icons/cart.svg" alt="">
                See Sales
            </a>
        </li>
        <li class="pb-3">
            <a href="/admin/medicines" class="sidebar-link d-flex align-items-center gap-2">
                <img src="/assets/icons/pill.svg" alt="">
                Manage Medicines
            </a>
        </li>
        <li class="pb-3">
            <a href="/admin/stock" class="sidebar-link d-flex align-items-center gap-2">
                <img src="/assets/icons/inventory.svg" alt="">
                Manage Stock
            </a>
        </li>
        <li class="pb-3">
            <a href="/admin/medicine-categories" class="sidebar-link d-flex align-items-center gap-2">
                <img src="/assets/icons/category.svg" alt="">
                Manage Categories
            </a>
        </li>
        <li class="pb-3">
            <a href="/admin/employees" class="sidebar-link d-flex align-items-center gap-2">
                <img src="/assets/icons/people.svg" alt="">
                Manage Cashiers
            </a>
        </li>
    </ul>

    <form class="mt-auto" action="/logout" method="post">
        <button class="btn btn-secondary w-100" type="submit" name="logout">Log Out</button>
    </form>
</aside>