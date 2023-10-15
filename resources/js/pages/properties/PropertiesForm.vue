<script setup>
import axios from 'axios';
import { reactive, onMounted, ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useToastr } from '@/toastr';
import { Form } from 'vee-validate';
import flatpickr from "flatpickr";
import 'flatpickr/dist/themes/light.css';

const addNewLineItemForm = () => {
    var count = form.lineitems.length + 1;
    form.lineitems.push({ title: '', value: '', value_type: 'fixed', apply_on: '', is_required: '', display_order: count, image: 'placeholder.png' })
};
const deleteLineItemForm = (index) => {
    form.lineitems.splice(index, 1)
};

const addNewPriceForm = () => {
    var count = form.prices.length + 1;
    form.prices.push({ name: '', price: '', display_order: count, date_start: '', date_end: '', image: 'placeholder.png' })
};
const deletePriceForm = (index) => {
    form.prices.splice(index, 1)
};

const addNewServiceForm = () => {
    var count = form.services.length + 1;
    form.services.push({ title: '', price: '', display_order: count, description: '', image: 'placeholder.png' })
};
const deleteServiceForm = (index) => {
    form.services.splice(index, 1)
};


const addNewNeighbourForm = () => {
    var count = form.neighbours.length + 1;
    form.neighbours.push({ name: '', distance: '', display_order: count, description: '', icon: '', image: 'placeholder.png' })
};
const deleteNeighbourForm = (index) => {
    form.neighbours.splice(index, 1)
};

const addNewRoomForm = () => {
    var count = form.rooms.length + 1;
    form.rooms.push({ name: '', type: '', display_order: count, description: '', icon: '', image: 'placeholder.png' })
};
const deleteRoomForm = (index) => {
    form.rooms.splice(index, 1)
};


const router = useRouter();
const route = useRoute();
const toastr = useToastr();
const form = reactive({
    name: '',
    slug: '',
    item_code: '',
    description: '',
    amenities: [],
    bedrooms: '',
    bathrooms: '',
    price: '',
    property_type_id: '',
    excerpt: '',
    features: [],
    services: [],
    lineitems: [],
    neighbours: [],
    rooms: [],
    prices: [],
    bookings: [],
    price_sale: '',
    price_nightly: '',
    price_weekly: '',
    price_two_weekly: '',
    price_monthly: '',
    catchwords: '',
    min_stay: '',
    capacity: '',
    parkings: '',
    is_child_friendly: true, // Set default value
    is_pets_allowed: false, // Set default value
    address: '',
    location_id: '',
    latitude: '',
    longitude: '',
    first_payment_percentage: '',
    second_payment_percentage: '',
    third_payment_percentage: '',
    first_payment_days: '',
    second_payment_days: '',
    third_payment_days: '',
    image: 'placeholder.png', // Default image
    image_id: '',
    display_order: '',
    is_active: true, // Set default value
    is_featured: false, // Set default value
    is_new: true, // Set default value
    is_vacation: true, // Set default value
    is_monthly: false, // Set default value
    is_sale: false, // Set default value
    is_sale_policies: true,
    sale_policies: '',
    is_rental_policies: true,
    rental_policies: '',
    admin_notes: '',
    user_id: '',
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
const getAvailablePropertyTypes = () => {
    axios.get('/api/propertytypes')
        .then((response) => {
            propertyTypes.value = response.data;
        })
};

const locations = ref();
const getAvailableLocations = () => {
    axios.get('/api/locations')
        .then((response) => {
            locations.value = response.data;
        })
};

const availableAmenities = ref();
const getAvailableAmenities = () => {
    axios.get('/api/amenities')
        .then((response) => {
            availableAmenities.value = response.data;
        })
};
const availableFeatures = ref();
const getAvailableFeatures = () => {
    axios.get('/api/features')
        .then((response) => {
            availableFeatures.value = response.data;
        })
};

const getProperty = () => {
    axios.get(`/api/properties/${route.params.id}/edit`)
        .then(({ data }) => {
            form.name = data.name;
            form.slug = data.slug;
            form.item_code = data.item_code;
            form.description = data.description;
            form.property_type_id = data.property_type_id;
            form.excerpt = data.excerpt;
            form.amenities = data.associated_amenities;
            form.features = data.associated_features;
            form.services = data.services;
            form.lineitems = data.lineitems;
            form.neighbours = data.neighbours;
            form.rooms = data.rooms;
            form.bookings = data.bookings;
            form.prices = data.prices;
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
            form.is_child_friendly = data.is_child_friendly ? true : false;
            form.is_pets_allowed = data.is_pets_allowed ? true : false;
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
            form.display_order = data.display_order;
            form.is_active = data.is_active ? true : false;
            form.is_featured = data.is_featured ? true : false;
            form.is_new = data.is_new ? true : false;
            form.is_vacation = data.is_vacation ? true : false;
            form.is_monthly = data.is_monthly ? true : false;
            form.is_sale = data.is_sale ? true : false;
            form.is_sale_policies = data.is_sale_policies ? true : false;
            form.sale_policies = data.sale_policies;
            form.is_rental_policies = data.is_rental_policies ? true : false;
            form.rental_policies = data.rental_policies;
            form.admin_notes = data.admin_notes;
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
    getAvailablePropertyTypes();
    getAvailableLocations();
    getAvailableAmenities();
    getAvailableFeatures();
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
                        Property
                    </h1>
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
                        <div class="card-body ">
                            <Form @submit="handleSubmit" v-slot="{ errors }">
                                <ul class="nav nav-pills">
                                    <li class="nav-item"><a class="nav-link active show" href="#basic-detail"
                                            data-toggle="tab">Basic Details</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#address" data-toggle="tab">Address</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#description"
                                            data-toggle="tab">Description</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#features" data-toggle="tab">Features</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#amenities"
                                            data-toggle="tab">Amenities</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#rooms"
                                            data-toggle="tab">Rooms/Floors</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#neighbours"
                                            data-toggle="tab">Neighbors</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#services" data-toggle="tab">Services</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#lineitems" data-toggle="tab">Line
                                            Items</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#prices" data-toggle="tab">Prices</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#calendar" data-toggle="tab">Calendar</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#management"
                                            data-toggle="tab">Management</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#bookings"
                                            data-toggle="tab">Bookings</a></li>
                                    <li class="nav-item"><a class="nav-link" href="#reports" data-toggle="tab">Reports</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#statistics"
                                            data-toggle="tab">Statistics</a></li>
                                </ul>
                                <hr />
                                <div class="tab-content">
                                    <!-- Tab Tab -->
                                    <div class="tab-pane active show" id="basic-detail">
                                        <h3 class="text-center">Primary Details</h3>

                                        <div class="row ">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="name">Property Name</label>
                                                    <input v-model="form.name" type="text" class="form-control"
                                                        :class="{ 'is-invalid': errors.name }" id="name"
                                                        placeholder="Enter Property Name">
                                                    <span class="invalid-feedback">{{ errors.name }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="property-type">Property Type</label>
                                                    <!-- You can create a select element for property types here -->
                                                    <select v-model="form.property_type_id" id="property-type"
                                                        class="form-control"
                                                        :class="{ 'is-invalid': errors.property_type_id }">
                                                        <option value="" disabled>Select Property Type</option>
                                                        <!-- Populate options based on property types -->
                                                        <option v-for="propertyType in propertyTypes"
                                                            :value="propertyType.id" :key="propertyType.id">{{
                                                                propertyType.name }}</option>
                                                    </select>
                                                    <span class="invalid-feedback">{{ errors.property_type_id }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="slug">Item Code</label>
                                                    <input v-model="form.item_code" type="text" class="form-control"
                                                        :class="{ 'is-invalid': errors.item_code }" id="item_code"
                                                        placeholder="Enter Item Code">
                                                    <span class="invalid-feedback">{{ errors.item_code }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="slug">Slug</label>
                                                    <input v-model="form.slug" type="text" class="form-control"
                                                        :class="{ 'is-invalid': errors.slug }" id="slug"
                                                        placeholder="Enter Slug">
                                                    <span class="invalid-feedback">{{ errors.slug }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_active">
                                                            <input v-model="form.is_active" class="form-check-input"
                                                                type="checkbox" id="is_active">
                                                            Active</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{ errors.is_active }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_featured">
                                                            <input v-model="form.is_featured" class="form-check-input"
                                                                type="checkbox" id="is_featured">
                                                            Featured</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{ errors.is_featured }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_new">
                                                            <input v-model="form.is_new" class="form-check-input"
                                                                type="checkbox" id="is_new">
                                                            New</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{ errors.is_new }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_vacation">
                                                            <input v-model="form.is_vacation" class="form-check-input"
                                                                type="checkbox" id="is_vacation">
                                                            Vacation</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{ errors.is_vacation }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_monthly">
                                                            <input v-model="form.is_monthly" class="form-check-input"
                                                                type="checkbox" id="is_monthly">
                                                            Monthly</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{ errors.is_monthly }}</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_sale">
                                                            <input v-model="form.is_sale" class="form-check-input"
                                                                type="checkbox" id="is_sale">
                                                            Sale</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{ errors.is_sale }}</span>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_pets_allowed">
                                                            <input v-model="form.is_pets_allowed" class="form-check-input"
                                                                type="checkbox" id="is_pets_allowed">
                                                            Pets Allowed</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{ errors.is_pets_allowed }}</span>
                                                </div>
                                            </div>

                                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-6">
                                                <div class="form-group">
                                                    <div class="form-check">
                                                        <label for="is_child_friendly">
                                                            <input v-model="form.is_child_friendly" class="form-check-input"
                                                                type="checkbox" id="is_child_friendly">
                                                            Child Friendly</label>
                                                    </div>
                                                    <span class="invalid-feedback">{{ errors.is_child_friendly }}</span>
                                                </div>
                                            </div>


                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bedrooms">Bedrooms</label>
                                                    <input v-model="form.bedrooms" type="number" class="form-control"
                                                        :class="{ 'is-invalid': errors.bedrooms }" id="bedrooms">
                                                    <span class="invalid-feedback">{{ errors.bedrooms }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="bathrooms">Bathrooms</label>
                                                    <input v-model="form.bathrooms" type="number" step="0.1"
                                                        class="form-control" :class="{ 'is-invalid': errors.bathrooms }"
                                                        id="bathrooms">
                                                    <span class="invalid-feedback">{{ errors.bathrooms }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="min_stay">Minimum Stay</label>
                                                    <input v-model="form.min_stay" type="number" class="form-control"
                                                        :class="{ 'is-invalid': errors.min_stay }" id="min_stay">
                                                    <span class="invalid-feedback">{{ errors.min_stay }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="parkings">Parkings</label>
                                                    <input v-model="form.parkings" type="number" class="form-control"
                                                        :class="{ 'is-invalid': errors.parkings }" id="parkings">
                                                    <span class="invalid-feedback">{{ errors.parkings }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="capacity">Capacity</label>
                                                    <input v-model="form.capacity" type="number" class="form-control"
                                                        :class="{ 'is-invalid': errors.capacity }" id="capacity">
                                                    <span class="invalid-feedback">{{ errors.capacity }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="display_order">Display Order</label>
                                                    <input v-model="form.display_order" type="number" class="form-control"
                                                        :class="{ 'is-invalid': errors.display_order }" id="display_order">
                                                    <span class="invalid-feedback">{{ errors.display_order }}</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="address">
                                        <h3 class="text-center">Address</h3>
                                        <div class="row ">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <input v-model="form.address" type="text" class="form-control"
                                                        :class="{ 'is-invalid': errors.address }" id="address"
                                                        placeholder="Enter Property Address">
                                                    <span class="invalid-feedback">{{ errors.address }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="location-id">Location</label>
                                                    <select v-model="form.location_id" id="location-id" class="form-control"
                                                        :class="{ 'is-invalid': errors.location_id }">
                                                        <option value="" disabled>Select Location</option>
                                                        <!-- Populate options based on locations -->
                                                        <option v-for="location in locations" :value="location.id"
                                                            :key="location.id">{{
                                                                location.name }}</option>
                                                    </select>
                                                    <span class="invalid-feedback">{{ errors.location_id }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="description">
                                        <h3 class="text-center">Description</h3>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="excerpt">Excerpt</label>
                                                    <textarea v-model="form.excerpt" class="form-control"
                                                        :class="{ 'is-invalid': errors.excerpt }" id="excerpt" rows="3"
                                                        placeholder="Enter Excerpt"></textarea>
                                                    <span class="invalid-feedback">{{ errors.excerpt }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="description">Description</label>
                                                    <textarea v-model="form.description" class="form-control"
                                                        :class="{ 'is-invalid': errors.description }" id="description"
                                                        rows="3"
                                                        placeholder="Enter property description here..."></textarea>
                                                    <span class="invalid-feedback">{{ errors.description }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="features">
                                        <h3 class="text-center">Features</h3>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <!-- Iterate through available features -->
                                                    <div class="checkbox-item col-lg-3 col-md-4 col-sm-6 col-sm-12"
                                                        v-for="feature in availableFeatures" :key="feature.id">
                                                        <label class="checkbox-label">
                                                            <input type="checkbox" v-model="form.features"
                                                                :value="feature.id" name="features[]" />
                                                            {{ feature.name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="amenities">
                                        <h3 class="text-center">Amenities</h3>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <!-- Iterate through available amenities -->
                                                    <div class="checkbox-item col-lg-3 col-md-4 col-sm-6 col-sm-12"
                                                        v-for="amenity in availableAmenities" :key="amenity.id">
                                                        <label class="checkbox-label">
                                                            <input type="checkbox" v-model="form.amenities"
                                                                :value="amenity.id" name="amenities[]" />
                                                            {{ amenity.name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="rooms">
                                        <h3 class="text-center">Rooms / Floors</h3>
                                        <div class="card mb-3" v-for="(room, index) in form.rooms">
                                            <div class="card-body">
                                                <span class="float-right" style="cursor:pointer"
                                                    @click="deleteRoomForm(index)">X</span>
                                                <h4>Add Room/Floor (index: {{ index + 1 }})</h4>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Name:</label>
                                                            <input type="text" v-model="room.name" class="form-control mb-2"
                                                                placeholder="Name" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Type:</label><br />
                                                            <input type="radio" :id="'room' + index" value="room"
                                                                v-model="room.type">
                                                            <label :for="'room' + index">Room</label>
                                                            <input type="radio" :id="'floor' + index" value="floor"
                                                                v-model="room.type">
                                                            <label :for="'floor' + index">Floor</label>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Order:</label>
                                                            <input type="number" v-model="room.display_order"
                                                                class="form-control mb-2" placeholder="#" />
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <textarea v-model="room.description" class="form-control" cols="30"
                                                            rows="10" placeholder="Description"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container text-center">
                                            <button @click="addNewRoomForm" type="button" class="btn btn-sm btn-info">
                                                <i class="fa fa-plus"></i> New Room
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="neighbours">
                                        <h3 class="text-center">Nearby Places</h3>
                                        <div class="card mb-3" v-for="(neighbour, index) in form.neighbours">
                                            <div class="card-body">
                                                <span class="float-right" style="cursor:pointer"
                                                    @click="deleteNeighbourForm(index)">
                                                    X
                                                </span>
                                                <h4>Add Neighbor (index: {{ index + 1 }})</h4>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label>Name:</label>
                                                        <input type="text" v-model="neighbour.name"
                                                            class="form-control mb-2"
                                                            placeholder="Hospital, School, Beach" />
                                                        <label>Order:</label>
                                                        <input type="number" v-model="neighbour.display_order"
                                                            class="form-control mb-2" placeholder="#" />
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Distance:</label>
                                                        <input type="text" v-model="neighbour.distance"
                                                            class="form-control mb-2" placeholder="5 miles" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="container text-center">
                                            <button @click="addNewNeighbourForm" type="button" class="btn btn-sm btn-info">
                                                <i class="fa fa-plus"></i> New Neighbor
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="lineitems">
                                        <h3 class="text-center">Line Items</h3>
                                        <div class="card mb-3" v-for="(lineitem, index) in form.lineitems">
                                            <div class="card-body">
                                                <span class="float-right" style="cursor:pointer"
                                                    @click="deleteLineItemForm(index)">
                                                    X
                                                </span>
                                                <h4>Add Line Item #{{ index + 1 }}</h4>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label>Name:</label>
                                                            <input type="text" v-model="lineitem.name"
                                                                class="form-control mb-2" placeholder="Name" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label
                                                                :for="'lineitem_is_required' + index">Required</label><br />
                                                            <input type="checkbox" v-model="lineitem.is_required"
                                                                :id="'lineitem_is_required' + index" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Order:</label>
                                                            <input type="number" v-model="lineitem.display_order"
                                                                class="form-control mb-2" placeholder="#" />
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="form-group">
                                                            <label v-if="lineitem.value_type == 'percentage'">Value:
                                                            </label>
                                                            <label v-else>Price: </label>
                                                            <div class="input-group">
                                                                <div v-if="lineitem.value_type == 'fixed'"
                                                                    class="input-group-prepend">
                                                                    <span class="input-group-text">$</span>
                                                                </div>
                                                                <input type="number" v-model="lineitem.value"
                                                                    class="form-control" placeholder="0.00" />
                                                                <div v-if="lineitem.value_type == 'percentage'"
                                                                    class="input-group-append">
                                                                    <span class="input-group-text">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Type:</label><br />
                                                            <input type="radio" :id="'fixed' + index" value="fixed"
                                                                v-model="lineitem.value_type">
                                                            <label :for="'fixed' + index">Fixed Price</label>
                                                            <input type="radio" :id="'percent' + index" value="percentage"
                                                                v-model="lineitem.value_type">
                                                            <label :for="'percent' + index">Percentage</label>
                                                        </div>
                                                        <div class="form-group"
                                                            v-if="lineitem.value_type == 'percentage' && lineitem.display_order > 1">
                                                            <label>Apply To:</label><br />
                                                            <input type="radio" :id="'base' + index" value="base"
                                                                v-model="lineitem.apply_on">
                                                            <label :for="'base' + index">Lodging Amount Only</label>
                                                            <input type="radio" :id="'sum' + index" value="sum"
                                                                v-model="lineitem.apply_on">
                                                            <label :for="'sum' + index">Sum of Line Items</label>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="container text-center">
                                            <button @click="addNewLineItemForm" type="button" class="btn btn-sm btn-info">
                                                <i class="fa fa-plus"></i> New LineItem
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="services">
                                        <h3 class="text-center">Services</h3>
                                        <div class="card mb-3" v-for="(service, index) in form.services">
                                            <div class="card-body">
                                                <span class="float-right" style="cursor:pointer"
                                                    @click="deleteServiceForm(index)">
                                                    X
                                                </span>
                                                <h4>Add Service (index: {{ index + 1 }})</h4>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label>Name:</label>
                                                        <input type="text" v-model="service.name" class="form-control mb-2"
                                                            placeholder="Name" />
                                                        <label>Price $:</label>
                                                        <input type="number" v-model="service.price"
                                                            class="form-control mb-2" placeholder="0.00" />
                                                        <label>Order:</label>
                                                        <input type="number" v-model="service.display_order"
                                                            class="form-control mb-2" placeholder="#" />
                                                    </div>
                                                    <div class="col-6">
                                                        <textarea v-model="service.description" class="form-control"
                                                            cols="30" rows="10" placeholder="Description"></textarea>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="container text-center">
                                            <button @click="addNewServiceForm" type="button" class="btn btn-sm btn-info">
                                                <i class="fa fa-plus"></i> New Service
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="prices">
                                        <h3 class="text-center">Regular Prices</h3>
                                        <div class="form-group">
                                            <label>Price Nightly: $</label>
                                            <input v-model="form.price_nightly" type="number" name="price_nightly"
                                                placeholder="000.00" class="form-control"
                                                :class="{ 'is-invalid': errors.price_nightly }">
                                            <span class="invalid-feedback">{{ errors.price_nightly }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label>Price Monthly: $</label>
                                            <input v-model="form.price_monthly" type="number" placeholder="000.00"
                                                class="form-control" :class="{ 'is-invalid': errors.price_monthly }">
                                            <span class="invalid-feedback">{{ errors.price_monthly }}</span>
                                        </div>
                                        <div class="form-group">
                                            <label>Price Sale: $</label>
                                            <input v-model="form.price_sale" type="number" name="price_sale"
                                                placeholder="0000000.00" class="form-control"
                                                :class="{ 'is-invalid': errors.price_sale }">
                                            <span class="invalid-feedback">{{ errors.price_sale }}</span>
                                        </div>
                                        <h3 class="text-center">Season Prices</h3>
                                        <div class="card mb-3" v-for="(price, index) in form.prices">
                                            <div class="card-body">
                                                <span class="float-right" style="cursor:pointer"
                                                    @click="deletePriceForm(index)">
                                                    X
                                                </span>
                                                <h4>Add price (index: {{ index + 1 }})</h4>
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label>Season Name:</label>
                                                        <input type="text" v-model="price.name" class="form-control mb-2"
                                                            placeholder="Summer, Winter, Christmas" />
                                                        <label>Price $:</label>
                                                        <input type="number" v-model="price.price" class="form-control mb-2"
                                                            placeholder="0.00" />
                                                        <label>Order:</label>
                                                        <input type="number" v-model="price.display_order"
                                                            class="form-control mb-2" placeholder="#" />
                                                    </div>
                                                    <div class="col-6">
                                                        <label>Date Start:</label>
                                                        <input type="date" v-model="price.date_start"
                                                            class="form-control mb-2" placeholder="yyyy-mm-dd" />
                                                        <label>Date End:</label>
                                                        <input type="date" v-model="price.date_end"
                                                            class="form-control mb-2" placeholder="yyyy-mm-dd" />
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                        <div class="container text-center">
                                            <button @click="addNewPriceForm" type="button" class="btn btn-sm btn-info">
                                                <i class="fa fa-plus"></i> New Price
                                            </button>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="calendar">



                                        <div class="col-md-12">
                                            Calendar is loading... v-html="calendar_html"
                                        </div>

                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="management">
                                        <h3 class="text-center">Management</h3>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="admin_notes">Admin Notes</label>
                                                    <textarea v-model="form.admin_notes" class="form-control"
                                                        :class="{ 'is-invalid': errors.admin_notes }" id="admin_notes"
                                                        rows="3"
                                                        placeholder="add notes here..."></textarea>
                                                    <span class="invalid-feedback">{{ errors.admin_notes }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Tab Pan-->

                                    <div class="tab-pane" id="bookings">
                                        <h3 class="text-center">Bookings</h3>
                                        <table width="100%">
                                            <thead>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>From Date</th>
                                                <th>To Date</th>
                                            </thead>
                                            <tr v-for="booking in form.bookings">
                                                <td>{{booking.id}}</td>
                                                <td>{{booking.firstname}} {{booking.lastname}}</td>
                                                <td>{{booking.date_start}}</td>
                                                <td>{{booking.date_end}}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="reports">
                                        <h3 class="text-center">Reports</h3>
                                        Show reports here
                                    </div>
                                    <!-- Tab Pan-->
                                    <div class="tab-pane" id="statistics">
                                        <h3 class="text-center">Statistics</h3>

                                    </div>
                                    <!-- /.tab-pane -->
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </Form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.checkbox-item {
    float: left;
}

.checkbox-label {
    font-weight: normal !important;
}

.checkbox-label input[type="checkbox"] {
    margin-right: 8px;
}
</style>