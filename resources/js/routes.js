import Dashboard from './components/Dashboard.vue';
import ListAppointments from './pages/appointments/ListAppointments.vue';
import AppointmentForm from './pages/appointments/AppointmentForm.vue';
import ListProperties from './pages/properties/ListProperties.vue';
import PropertiesForm from './pages/properties/PropertiesForm.vue';
import ListPropertyTypes from './pages/propertytypes/ListPropertyTypes.vue';
import PropertyTypeForm from './pages/propertytypes/PropertyTypeForm.vue';
import UserList from './pages/users/UserList.vue';
import UpdateSetting from './pages/settings/UpdateSetting.vue';
import UpdateProfile from './pages/profile/UpdateProfile.vue';
import Login from './pages/auth/Login.vue';

export default [
    {
        path: '/login',
        name: 'admin.login',
        component: Login,
    },

    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: Dashboard,
    },

    {
        path: '/admin/appointments',
        name: 'admin.appointments',
        component: ListAppointments,
    },

    {
        path: '/admin/appointments/create',
        name: 'admin.appointments.create',
        component: AppointmentForm,
    },

    {
        path: '/admin/appointments/:id/edit',
        name: 'admin.appointments.edit',
        component: AppointmentForm,
    },
        
    {
        path: '/admin/properties',
        name: 'admin.properties',
        component: ListProperties,
    },

    {
        path: '/admin/properties/create',
        name: 'admin.properties.create',
        component: PropertiesForm,
    },

    {
        path: '/admin/properties/:id/edit',
        name: 'admin.properties.edit',
        component: PropertiesForm,
    },

    {
        path: '/admin/propertytypes/create',
        name: 'admin.propertytypes.create',
        component: PropertyTypeForm,
    },

    {
        path: '/admin/propertytypes/:id/edit',
        name: 'admin.propertytypes.edit',
        component: PropertyTypeForm,
    },

    {
        path: '/admin/propertytypes',
        name: 'admin.propertytypes',
        component: ListPropertyTypes,
    },


    {
        path: '/admin/users',
        name: 'admin.users',
        component: UserList,
    },

    {
        path: '/admin/settings',
        name: 'admin.settings',
        component: UpdateSetting,
    },

    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: UpdateProfile,
    }
]
