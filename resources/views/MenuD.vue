<template>
    <div class="max-w-7xl mx-auto p-4">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Table D - Sales</h1>
            <div class="flex space-x-2">
                <button @click="openUploadModal" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                    Upload Excel
                </button>
                <button @click="openCreateModal" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Create Data
                </button>
                <button @click="downloadExcel" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                    Download Excel
                </button>
                <button @click="downloadPdf" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                    Download PDF
                </button>
            </div>
        </div>

        <!-- Data Table -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <vue-good-table :search-options="{ enabled: true, trigger: 'enter', placeholder: 'Search kode sales...' }"
                :mode="'remote'" @search="searchFunc" @page-change="onPageChange" @per-page-change="onPerPageChange"
                :totalRows="total" :isLoading.sync="isLoading" :pagination-options="{ enabled: true }"
                styleClass="vgt-table tailwind" :rows="rows" :columns="columns">
                <template #table-row="props">
                    <span v-if="props.column.field === 'actions'">
                        <button @click="openEditModal(props.row)" class="text-blue-600 hover:text-blue-800 mr-2">
                            Edit
                        </button>
                        <button @click="confirmDelete(props.row)" class="text-red-600 hover:text-red-800">
                            Delete
                        </button>
                    </span>
                    <span v-else>
                        {{ props.formattedRow[props.column.field] }}
                    </span>
                </template>
            </vue-good-table>
            <p class="mt-2">Total from API: {{ total }}</p>
        </div>

        <!-- Modal Create/Edit -->
        <BModal v-model="showModal" @update:model-value="closeModal"
            :title="isEditMode ? `Edit Sales #${form.kode_sales}` : 'Create Data'">
            <div v-if="errorMessage" class="mb-3 text-danger bg-light p-2 rounded">
                {{ errorMessage }}
            </div>
            <form @submit.prevent="submitForm">
                <div class="mb-3">
                    <label class="form-label">Kode Sales</label>
                    <input v-model="form.kode_sales" type="text" class="form-control" :disabled="isEditMode" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Nama Sales</label>
                    <input v-model="form.nama_sales" type="text" class="form-control" required />
                </div>
            </form>

            <template #footer>
                <BButton variant="secondary" @click="closeModal(false)">Cancel</BButton>
                <BButton variant="primary" @click="submitForm">
                    {{ isEditMode ? 'Update' : 'Create' }}
                </BButton>
            </template>
        </BModal>

        <!-- Modal Delete -->
        <BModal v-model="showDeleteModal" title="Delete Data">
            <div class="text-danger mb-4">
                Are you sure you want to delete <strong>Kode {{ dataToDelete?.kode_sales }}</strong>?
            </div>
            <template #footer>
                <BButton variant="secondary" @click="closeDeleteModal">Cancel</BButton>
                <BButton variant="danger" @click="deleteConfirmed">Delete</BButton>
            </template>
        </BModal>

        <!-- Modal Upload Excel -->
        <BModal v-model="showUploadModal" title="Upload Excel Table D">
            <form @submit.prevent="submitUpload">
                <input type="file" @change="handleFileChange" accept=".xlsx,.xls,.csv" class="form-control mb-3" />
            </form>
            <template #footer>
                <BButton variant="secondary" @click="closeUploadModal">Cancel</BButton>
                <BButton variant="success" @click="submitUpload" :disabled="!selectedFile">
                    Upload
                </BButton>
            </template>
        </BModal>
    </div>
</template>

<script>
import apiClient from "../src/axios";
import { BModal, BButton } from "bootstrap-vue-next";
import { handleApiError } from "../src/errorHandler";
import moment from "moment";

export default {
    name: "MenuD",
    components: { BModal, BButton },
    data() {
        return {
            rows: [],
            columns: [
                { label: "Kode Sales", field: "kode_sales" },
                { label: "Nama Sales", field: "nama_sales" },
                { label: "Actions", field: "actions", sortable: false },
            ],
            search: "",
            perPage: 10,
            total: 0,
            currentPage: 1,
            isLoading: false,
            showModal: false,
            isEditMode: false,
            form: { kode_sales: "", nama_sales: "" },
            errorMessage: "",
            showDeleteModal: false,
            dataToDelete: null,
            showUploadModal: false,
            selectedFile: null,
        };
    },
    methods: {
        async fetchData() {
            this.isLoading = true;
            try {
                const res = await apiClient.get("/table-d", {
                    params: {
                        search: this.search,
                        page: this.currentPage,
                        per_page: this.perPage,
                    },
                });
                this.rows = res.data.data;
                this.total = Number(res.data.total);
            } catch (e) {
                console.error("Error fetch:", e);
            } finally {
                this.isLoading = false;
            }
        },
        searchFunc(params) {
            this.search = params.searchTerm;
            this.currentPage = 1;
            this.fetchData();
        },
        onPageChange(params) {
            this.currentPage = params.currentPage;
            this.fetchData();
        },
        onPerPageChange(params) {
            this.perPage = params.currentPerPage;
            this.currentPage = 1;
            this.fetchData();
        },
        openCreateModal() {
            this.isEditMode = false;
            this.form = { kode_sales: "", nama_sales: "" };
            this.showModal = true;
        },
        openEditModal(row) {
            this.isEditMode = true;
            this.form = { ...row };
            this.showModal = true;
        },
        closeModal(val = false) {
            this.showModal = val;
            this.errorMessage = "";
        },
        async submitForm() {
            try {
                if (this.isEditMode) {
                    await apiClient.put(
                        `/table-d/${this.form.kode_sales}`,
                        this.form
                    );
                } else {
                    await apiClient.post("/table-d", this.form);
                }
                this.fetchData();
                this.closeModal(false);
            } catch (e) {
                handleApiError(e);
            }
        },
        confirmDelete(row) {
            this.dataToDelete = row;
            this.showDeleteModal = true;
        },
        closeDeleteModal() {
            this.showDeleteModal = false;
            this.dataToDelete = null;
        },
        async deleteConfirmed() {
            if (!this.dataToDelete) return;
            try {
                await apiClient.delete(
                    `/table-d/${this.dataToDelete.kode_sales}`
                );
                this.fetchData();
                this.closeDeleteModal();
            } catch (e) {
                handleApiError(e);
            }
        },
        // Upload Excel
        openUploadModal() {
            this.showUploadModal = true;
            this.selectedFile = null;
        },
        closeUploadModal() {
            this.showUploadModal = false;
        },
        handleFileChange(e) {
            this.selectedFile = e.target.files[0];
        },
        async submitUpload() {
            if (!this.selectedFile) return;
            const formData = new FormData();
            formData.append("file", this.selectedFile);
            try {
                await apiClient.post(
                    "/table-d/upload-excel",
                    formData,
                    {
                        headers: { "Content-Type": "multipart/form-data" },
                    }
                );
                this.fetchData();
                this.closeUploadModal();
            } catch (e) {
                handleApiError(e);
            }
        },
        async downloadExcel() {
            try {
                const res = await apiClient.get('/table-d/export-excel', {
                    params: {
                        search: this.search,
                        page: this.currentPage,
                        per_page: this.perPage,
                    },
                    responseType: 'blob',
                });
                const url = window.URL.createObjectURL(new Blob([res.data]));
                const link = document.createElement('a');
                const timestamp = moment().format("YYYYMMDD_HHmmss");
                link.href = url;
                link.setAttribute('download', `table_d${timestamp}.xlsx`);
                document.body.appendChild(link);
                link.click();
            } catch (err) {
                handleApiError(err);
            }
        },
        async downloadPdf() {
            try {
                const res = await apiClient.get('/table-d/export-pdf', {
                    params: {
                        search: this.search,
                        page: this.currentPage,
                        per_page: this.perPage,
                    },
                    responseType: 'blob',
                });
                const url = window.URL.createObjectURL(new Blob([res.data]));
                const link = document.createElement('a');
                const timestamp = moment().format("YYYYMMDD_HHmmss");
                link.href = url;
                link.setAttribute('download', `table_d${timestamp}.pdf`);
                document.body.appendChild(link);
                link.click();
            } catch (err) {
                handleApiError(err);
            }
        }
    },
    mounted() {
        this.fetchData();
    },
};
</script>