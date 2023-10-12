<script setup>
import axios from 'axios';
import { reactive, onMounted, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToastr } from '@/toastr';
import { Form } from 'vee-validate';
import flatpickr from "flatpickr";
import 'flatpickr/dist/themes/light.css';

const router = useRouter();
const route = useRoute();
const toastr = useToastr();
const form = reactive({
    name: '',
    bedrooms: '',
    bathrooms: '',
    price: '',
    property_type_id: '',
});

const handleSubmit = (values, actions) => {
    if (editMode.value) {
        editProperty(values, actions);
    } else {
        createProperty(values, actions);
    }
};

const createProperty = (values, actions) => {
    axios.post('/api/properties/create', form)
    .then((response) => {
        router.push('/admin/properties');
        toastr.success('Property created successfully!');
    })
    .catch((error) => {
        actions.setErrors(error.response.data.errors);
    })
};

const editProperty = (values, actions) => {
    axios.put(`/api/properties/${route.params.id}/edit`, form)
    .then((response) => {
        router.push('/admin/properties');
        toastr.success('Property updated successfully!');
    })
    .catch((error) => {
        actions.setErrors(error.response.data.errors);
    })
};

const propertyTypes = ref();
const getPropertyTypes = () => {
    axios.get('/api/propertytypes')
    .then((response) => {
        propertyTypes.value = response.data;
    })
};

const getProperty = () => {
    axios.get(`/api/properties/${route.params.id}/edit`)
    .then(({ data }) => {
        form.name = data.name;
        form.slug = data.slug;
        form.property_type_id = data.property_type_id;
        form.excerpt = data.excerpt;
        // You can continue setting the values for other properties based on your data.
        form.price = data.price;
        form.price_sale = data.price_sale;
        form.price_nightly = data.price_nightly;
        form.price_weekly = data.price_weekly;
        form.price_two_weekly = data.price_two_weekly;
        form.price_monthly = data.price_monthly;
        form.catchwords = data.catchwords;
        form.min_stay = data.min_stay;
        form.bedrooms = data.bedrooms;
        form.bathrooms = data.bathrooms;
        form.capacity = data.capacity;
        form.parkings = data.parkings;
        form.is_child_friendly = data.is_child_friendly;
        form.is_pets_allowed = data.is_pets_allowed;
        form.address = data.address;
        form.location_id = data.location_id;
        form.latitude = data.latitude;
        form.longitude = data.longitude;
        form.first_payment_percentage = data.first_payment_percentage;
        form.second_payment_percentage = data.second_payment_percentage;
        form.third_payment_percentage = data.third_payment_percentage;
        form.first_payment_days = data.first_payment_days;
        form.second_payment_days = data.second_payment_days;
        form.third_payment_days = data.third_payment_days;
        form.image = data.image;
        form.image_id = data.image_id;
        form.order = data.order;
        form.is_active = data.is_active;
        form.is_featured = data.is_featured;
        form.is_new = data.is_new;
        form.is_vacation = data.is_vacation;
        form.is_monthly = data.is_monthly;
        form.is_sale = data.is_sale;
        form.is_sale_policies = data.is_sale_policies;
        form.sale_policies = data.sale_policies;
        form.is_rental_policies = data.is_rental_policies;
        form.rental_policies = data.rental_policies;
        form.user_id = data.user_id;
    })
};


const editMode = ref(false);

onMounted(() => {
    if (route.name === 'admin.properties.edit') {
        editMode.value = true;
        getProperty();
    }

    flatpickr(".flatpickr", {
        enableTime: true,
        dateFormat: "Y-m-d h:i K",
        defaultHour: 10,
    });
    getPropertyTypes();
});
</script>

<template>
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <span v-if="editMode">Edit</span>
                        <span v-else>Create</span>
                        Property</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <router-link to="/admin/dashboard">Home</router-link>
                        </li>
                        <li class="breadcrumb-item">
                            <router-link to="/admin/properties">Properties</router-link>
                        </li>
                        <li class="breadcrumb-item active">
                            <span v-if="editMode">Edit</span>
                            <span v-else>Create</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <Form @submit="handleSubmit" v-slot="{ errors }">
                                <div class="row">
                                    <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Property Name</label>
                                        <input v-model="form.name" type="text" class="form-control" :class="{ 'is-invalid': errors.name }" id="name" placeholder="Enter Property Name">
                                        <span class="invalid-feedback">{{ errors.name }}</span>
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="slug">Item Code</label>
                                        <input v-model="form.item_code" type="text" class="form-control" :class="{ 'is-invalid': errors.item_code }" id="item_code" placeholder="Enter Item Code">
                                        <span class="invalid-feedback">{{ errors.item_code }}</span>
                                    </div>
                                    </div>
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="slug">Slug</label>
                                        <input v-model="form.slug" type="text" class="form-control" :class="{ 'is-invalid': errors.slug }" id="slug" placeholder="Enter Slug">
                                        <span class="invalid-feedback">{{ errors.slug }}</span>
                                    </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="property-type">Property Type</label>
                                        <!-- You can create a select element for property types here -->
                                        <select v-model="form.property_type_id" id="property-type" class="form-control" :class="{ 'is-invalid': errors.property_type_id }">
                                        <option value="" disabled>Select Property Type</option>
                                        <!-- Populate options based on property types -->
                                        <option v-for="propertyType in propertyTypes" :value="propertyType.id" :key="propertyType.id">{{ propertyType.name }}</option>
                                        </select>
                                        <span class="invalid-feedback">{{ errors.property_type_id }}</span>
                                    </div>
                                    </div>
                                    <!-- Continue adding other property fields as needed -->
                                </div>
                                <div class="form-group">
                                    <label for="excerpt">Excerpt</label>
                                    <textarea v-model="form.excerpt" class="form-control" :class="{ 'is-invalid': errors.excerpt }" id="excerpt" rows="3" placeholder="Enter Excerpt"></textarea>
                                    <span class="invalid-feedback">{{ errors.excerpt }}</span>
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea v-model="form.description" class="form-control" :class="{ 'is-invalid': errors.description }" id="description" rows="3" placeholder="Enter property description here..."></textarea>
                                    <span class="invalid-feedback">{{ errors.description }}</span>
                                </div>
                                <!-- Continue adding other property fields as needed -->
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </Form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
