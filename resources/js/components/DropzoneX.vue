<template>
  <div>
    <h1>File Upload Dropzone</h1>
    <vue-dropzone ref="myDropzone" id="my-dropzone" @vdropzone-success="onSuccess">
      <div class="dz-message">
        <div class="icon">
          <i class="fa fa-cloud-upload"></i>
        </div>
        <div class="message">
          Drag and drop files here or click to upload
        </div>
      </div>
    </vue-dropzone>
    <button @click="upload">Upload Files</button>
  </div>
</template>

<script>
import 'vue-dropzone/dist/vue2Dropzone.min.css'; // Import CSS

export default {
  methods: {
    onSuccess(file, response) {
      // Handle a successful upload
      console.log('File uploaded:', file);
      console.log('Server response:', response);
    },
    upload() {
      // Programmatically trigger the upload process
      this.$refs.myDropzone.processQueue();
    },
  },
};
</script>
