import axios from "axios";

const apiClient = axios.create({
  baseURL: import.meta.env.VITE_API_BASE_URL || "http://localhost:8000/api", // fallback
  headers: {
    "Content-Type": "application/json",
    Accept: "application/json",
  },
});

export default apiClient;