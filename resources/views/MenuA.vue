<template>
    <div class="max-w-7xl mx-auto p-4">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Table A</h1>
            <div class="flex gap-2">
                <button @click="openCreateModal" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                    Create
                </button>
                <label class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded cursor-pointer">
                    Upload Excel
                    <input type="file" class="hidden" @change="handleExcelUpload" accept=".xlsx, .xls, .csv" />
                </label>
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
            <vue-good-table :search-options="{
                enabled: true,
                trigger: 'enter',
                placeholder: 'Search kode toko baru...'
            }" :pagination-options="{ enabled: true }" :mode="'remote'" :rows="rows" :columns="columns"
                :totalRows="total" :isLoading.sync="isLoading" @page-change="onPageChange"
                @per-page-change="onPerPageChange" @search="searchFunc" styleClass="vgt-table tailwind">
                <template #table-row="props">
                    <span v-if="props.column.field === 'actions'">
                        <button @click="openEditModal(props.row)"
                            class="text-blue-600 hover:text-blue-800 mr-2">Edit</button>
                        <button @click="confirmDelete(props.row)"
                            class="text-red-600 hover:text-red-800">Delete</button>
                    </span>
                    <span v-else>
                        {{ props.formattedRow[props.column.field] }}
                    </span>
                </template>
            </vue-good-table>
            <p class="mt-2 text-gray-500">Total from API: {{ total }}</p>
        </div>

        <!-- Create/Edit Modal -->
        <BModal v-model="showModal" @update:model-value="closeModal"
            :title="isEditMode ? `Edit #${form.kode_toko_baru}` : 'Create New'">
            <form @submit.prevent="submitForm">
                <div class="mb-3">
                    <label class="form-label">Kode Toko Baru</label>
                    <input v-model="form.kode_toko_baru" type="number" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label class="form-label">Kode Toko Lama</label>
                    <input v-model="form.kode_toko_lama" type="number" class="form-control" />
                </div>
            </form>
            <template #footer>
                <BButton variant="secondary" @click="closeModal(false)">Cancel</BButton>
                <BButton variant="primary" @click="submitForm">{{ isEditMode ? 'Update' : 'Create' }}</BButton>
            </template>
        </BModal>

        <!-- Delete Modal -->
        <BModal v-model="showDeleteModal" title="Delete Confirmation">
            <div class="text-danger mb-4">
                Are you sure you want to delete record <strong>#{{ rowToDelete?.kode_toko_baru }}</strong>?
            </div>
            <template #footer>
                <BButton variant="secondary" @click="closeDeleteModal">Cancel</BButton>
                <BButton variant="danger" @click="deleteConfirmed">Delete</BButton>
            </template>
        </BModal>
    </div>
</template>

<script>
import { BModal, BButton } from 'bootstrap-vue-next'
import apiClient from "../src/axios";
import { handleApiError } from '../src/errorHandler';
import moment from "moment";

export default {
    name: 'MenuA',
    components: { BModal, BButton },
    data() {
        return {
            rows: [],
            columns: [
                { label: 'Kode Toko Baru', field: 'kode_toko_baru' },
                { label: 'Kode Toko Lama', field: 'kode_toko_lama' },
                { label: 'Actions', field: 'actions', sortable: false },
            ],
            total: 0,
            isLoading: false,
            search: '',
            perPage: 10,
            currentPage: 1,

            showModal: false,
            isEditMode: false,
            form: { kode_toko_baru: '', kode_toko_lama: '' },

            showDeleteModal: false,
            rowToDelete: null,
        }
    },
    methods: {
        async fetchData() {
            this.isLoading = true
            try {
                const res = await apiClient.get('/table-a', {
                    params: {
                        search: this.search,
                        page: this.currentPage,
                        per_page: this.perPage,
                    },
                })
                this.rows = res.data.data
                this.total = Number(res.data.total)
            } catch (err) {
                console.error('Error fetching data:', err)
                handleApiError(error);
            } finally {
                this.isLoading = false
            }
        },
        onPageChange(params) {
            this.currentPage = params.currentPage
            this.fetchData()
        },
        onPerPageChange(params) {
            this.perPage = params.currentPerPage
            this.currentPage = 1
            this.fetchData()
        },
        searchFunc(params) {
            this.search = params.searchTerm
            this.currentPage = 1
            this.fetchData()
        },
        openCreateModal() {
            this.isEditMode = false
            this.form = { kode_toko_baru: '', kode_toko_lama: '' }
            this.showModal = true
        },
        openEditModal(row) {
            this.isEditMode = true
            this.form = { ...row }
            this.showModal = true
        },
        closeModal(val = false) {
            this.showModal = val
        },
        async submitForm() {
            try {
                if (this.isEditMode) {
                    await apiClient.put(`/table-a/${this.form.kode_toko_baru}`, this.form)
                } else {
                    await apiClient.post('/table-a', this.form)
                }
                this.fetchData()
                this.closeModal(false)
            } catch (err) {
                handleApiError(err)
                console.error('Error saving data:', err)
            }
        },
        confirmDelete(row) {
            this.rowToDelete = row
            this.showDeleteModal = true
        },
        closeDeleteModal() {
            this.showDeleteModal = false
            this.rowToDelete = null
        },
        async deleteConfirmed() {
            if (!this.rowToDelete) return
            try {
                await apiClient.delete(`/table-a/${this.rowToDelete.kode_toko_baru}`)
                this.fetchData()
                this.closeDeleteModal()
            } catch (err) {
                handleApiError(err)
                console.error('Error deleting:', err)
            }
        },
        async handleExcelUpload(event) {
            const file = event.target.files[0]
            if (!file) return
            const formData = new FormData()
            formData.append('file', file)
            try {
                await apiClient.post('/table-a/upload-excel', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' },
                })
                this.fetchData()
            } catch (err) {
                handleApiError(err)
                console.error('Excel upload failed:', err)
            }
        },
        async downloadExcel() {
            try {
                const res = await apiClient.get('/table-a/export-excel', {
                    params: {
                        search: this.search,
                        page: this.currentPage,
                        per_page: this.perPage,
                    },
                    responseType: 'blob',
                });
                const timestamp = moment().format("YYYYMMDD_HHmmss");
                const url = window.URL.createObjectURL(new Blob([res.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `table_a${timestamp}.xlsx`);
                document.body.appendChild(link);
                link.click();
            } catch (err) {
                handleApiError(err);
            }
        },
        async downloadPdf() {
            try {
                const res = await apiClient.get('/table-a/export-pdf', {
                    params: {
                        search: this.search,
                        page: this.currentPage,
                        per_page: this.perPage,
                    },
                    responseType: 'blob',
                });
                const timestamp = moment().format("YYYYMMDD_HHmmss");
                const url = window.URL.createObjectURL(new Blob([res.data]));
                const link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', `table_a${timestamp}.pdf`);
                document.body.appendChild(link);
                link.click();
            } catch (err) {
                handleApiError(err);
            }
        }
    },
    mounted() {
        this.fetchData()
    }
}
</script>