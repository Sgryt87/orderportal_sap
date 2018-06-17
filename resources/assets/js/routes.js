import AdminDashboard from './components/admin/AdminDashboard';
import AdminOrderSingle from './components/admin/Orders/AdminOrderSingle';
import AdminOrderBulk from './components/admin/Orders/AdminOrderBulk';
import AdminOrdersHistory from './components/admin/Orders/AdminOrdersHistory';
import AdminItImport from './components/admin/AdminItImport';
import AdminItExport from './components/admin/AdminItExport';

export default [
    {path: '/admin/dashboard', component: AdminDashboard},
    {path: '/admin/orders/single', component: AdminOrderSingle},
    {path: '/admin/orders/bulk', component: AdminOrderBulk},
    {path: '/admin/orders/history', component: AdminOrdersHistory},
    {path: '/admin/it-imports', component: AdminItImport},
    {path: '/admin/it-exports', component: AdminItExport}
]