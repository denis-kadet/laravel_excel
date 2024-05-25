<script>
import MainLayout from "@/Layouts/MainLayout.vue";

export default {
    name: "Import",
    layout: MainLayout,
    data(){
        return {
            file: null,
        }
    },
    methods: {
        selectExcel: function () {
            this.$refs.file.click();
        },
        setExcel: function (e) {
            this.file = e.target.files[0];
        },
        importExcel: function () {
            const formData = new FormData;
            formData.append('file', this.file);

            this.$inertia.post('/projects/import', formData);
        },
    },
}
</script>

<template>
    <div class="">Import
        <div class="flex justify-center">
            <form>
                <input @change="setExcel" type="file" ref="file" class="hidden">
                <a @click.prevent="selectExcel" href="#" class="block rounded-full bg-green-600 w-32 text-center text-white p-2">Excel</a>
            </form>
            <div v-if="file" class="ml-3">
                <a @click.prevent="importExcel" href="#" class="block rounded-full bg-sky-600 w-32 text-center text-white p-2">Import</a>
            </div>
        </div>

    </div>
</template>

<style scoped>

</style>
