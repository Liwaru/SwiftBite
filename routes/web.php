<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\CustomerMenuController;
use App\Http\Controllers\CustomerPageController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WaiterController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.store');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('simple.auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::patch('/profile/name', [ProfileController::class, 'updateName'])->name('profile.name.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
});

Route::middleware(['simple.auth', 'user.level:1'])->group(function () {
    Route::get('/waiter', [WaiterController::class, 'dashboard'])->name('waiter.dashboard');
    Route::patch('/waiter/orders/{order}/complete', [WaiterController::class, 'complete'])->name('waiter.orders.complete');
});

Route::middleware(['simple.auth', 'user.level:2'])->group(function () {
    Route::get('/chef', [ChefController::class, 'dashboard'])->name('chef.dashboard');
    Route::get('/chef/orders', [ChefController::class, 'orders'])->name('chef.orders');
    Route::get('/chef/ingredients', [ChefController::class, 'ingredients'])->name('chef.ingredients');
    Route::post('/chef/ingredients/{ingredient}/use', [ChefController::class, 'useIngredient'])->name('chef.ingredients.use');
});

Route::middleware(['simple.auth', 'user.level:4'])->group(function () {
    Route::get('/manager', [ManagerController::class, 'dashboard'])->name('manager.dashboard');
    Route::post('/manager/users', [ManagerController::class, 'storeUser'])->name('manager.users.store');
    Route::patch('/manager/users/{user}', [ManagerController::class, 'updateUser'])->name('manager.users.update');
    Route::post('/manager/tables', [ManagerController::class, 'storeTable'])->name('manager.tables.store');
    Route::patch('/manager/tables/{table}', [ManagerController::class, 'updateTable'])->name('manager.tables.update');
    Route::delete('/manager/tables/{table}', [ManagerController::class, 'destroyTable'])->name('manager.tables.destroy');
    Route::post('/manager/menus', [ManagerController::class, 'storeMenu'])->name('manager.menus.store');
    Route::patch('/manager/menus/{menu}', [ManagerController::class, 'updateMenu'])->name('manager.menus.update');
    Route::delete('/manager/menus', [ManagerController::class, 'destroyMenus'])->name('manager.menus.destroy');
    Route::post('/manager/packages', [ManagerController::class, 'storePackage'])->name('manager.packages.store');
    Route::patch('/manager/packages/{package}', [ManagerController::class, 'updatePackage'])->name('manager.packages.update');
    Route::delete('/manager/packages/{package}', [ManagerController::class, 'destroyPackage'])->name('manager.packages.destroy');
    Route::post('/manager/ingredients', [ManagerController::class, 'storeIngredient'])->name('manager.ingredients.store');
    Route::patch('/manager/ingredients/{ingredient}', [ManagerController::class, 'updateIngredient'])->name('manager.ingredients.update');
    Route::delete('/manager/ingredients/{ingredient}', [ManagerController::class, 'destroyIngredient'])->name('manager.ingredients.destroy');
    Route::post('/manager/stock/scan', [ManagerController::class, 'scanStockBarcode'])->name('manager.stock.scan');
    Route::patch('/manager/stock/{menu}', [ManagerController::class, 'updateStock'])->name('manager.stock.update');
    Route::post('/manager/database/backup', [ManagerController::class, 'backupDatabase'])->name('manager.database.backup');
    Route::post('/manager/database/import', [ManagerController::class, 'importDatabase'])->name('manager.database.import');
    Route::delete('/manager/database/reset', [ManagerController::class, 'resetDatabase'])->name('manager.database.reset');
    Route::post('/manager/activity/data/{change}/restore', [ManagerController::class, 'restoreDataChange'])->name('manager.activity.restore');
    Route::get('/manager/{section}', [ManagerController::class, 'page'])->name('manager.page');
});

Route::middleware(['simple.auth', 'user.level:5'])->group(function () {
    Route::get('/owner', [OwnerController::class, 'dashboard'])->name('owner.dashboard');
    Route::get('/owner/sales', [OwnerController::class, 'sales'])->name('owner.sales');
    Route::get('/owner/finance', [OwnerController::class, 'finance'])->name('owner.finance');
    Route::get('/owner/products', [OwnerController::class, 'products'])->name('owner.products');
    Route::get('/owner/ingredients', [OwnerController::class, 'ingredients'])->name('owner.ingredients');
});

Route::middleware(['simple.auth', 'user.level:4,5'])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/admin/tables', [AdminController::class, 'storeTable'])->name('admin.tables.store');
    Route::post('/admin/menu-items', [AdminController::class, 'storeMenuItem'])->name('admin.menu-items.store');
    Route::patch('/admin/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
});

Route::middleware(['simple.auth', 'user.level:3'])->group(function () {
    Route::get('/kasir', [CashierController::class, 'dashboard'])->name('cashier.dashboard');
    Route::get('/kasir/pesanan', [CashierController::class, 'orders'])->name('cashier.orders');
    Route::post('/kasir/pesanan/scan', [CashierController::class, 'scanOrder'])->name('cashier.orders.scan');
    Route::post('/kasir/pesanan/menu-barcode', [CashierController::class, 'scanMenuBarcode'])->name('cashier.orders.menu-barcode');
    Route::post('/kasir/pesanan/langsung', [CashierController::class, 'storeDirectOrder'])->name('cashier.orders.direct-store');
    Route::get('/kasir/live-orders', [CashierController::class, 'liveOrders'])->name('cashier.orders.live');
    Route::get('/kasir/riwayat', [CashierController::class, 'history'])->name('cashier.history');
    Route::patch('/kasir/orders/{order}/status', [CashierController::class, 'updateOrderStatus'])->name('cashier.orders.status');
});

Route::middleware(['simple.auth', 'user.level:0'])->group(function () {
    Route::get('/pelanggan', [CustomerPageController::class, 'home'])->name('customer.home');
});

Route::get('/menu/{token}', [CustomerMenuController::class, 'show'])->name('customer.menu');
Route::post('/menu/{token}/orders', [CustomerMenuController::class, 'store'])->name('customer.orders.store');
Route::get('/menu/{token}/orders/{order}/payment', [CustomerMenuController::class, 'payment'])->name('customer.orders.payment');
Route::patch('/menu/{token}/orders/{order}/payment', [CustomerMenuController::class, 'confirmPayment'])->name('customer.orders.payment.confirm');
Route::post('/menu/{token}/orders/{order}/ewallet', [CustomerMenuController::class, 'createEwalletPayment'])->name('customer.orders.ewallet.create');
Route::post('/menu/{token}/orders/{order}/midtrans-sync', [CustomerMenuController::class, 'syncMidtransPayment'])->name('customer.orders.midtrans.sync');
Route::get('/menu/{token}/orders/{order}/qris', [CustomerMenuController::class, 'qris'])->name('customer.orders.qris');
Route::get('/menu/{token}/orders/{order}/qris/status', [CustomerMenuController::class, 'qrisStatus'])->name('customer.orders.qris.status');
Route::get('/menu/{token}/orders/{order}', [CustomerMenuController::class, 'receipt'])->name('customer.orders.show');
