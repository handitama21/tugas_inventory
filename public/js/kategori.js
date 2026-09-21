document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchKategori");
    const kategoriTable = document.getElementById("kategoriTable");

    if (searchInput && kategoriTable) {
        searchInput.addEventListener("input", function () {
            const keyword = this.value.toLowerCase().trim();
            const rows = kategoriTable.querySelectorAll("tbody tr");

            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();

                if (text.includes(keyword)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });
    }
});

function openAddKategoriModal() {
    const modal = document.getElementById("kategoriModal");
    const form = document.getElementById("kategoriForm");

    if (!modal || !form) {
        return;
    }

    form.reset();

    document.getElementById("id_kategori").value = "";

    document.getElementById("kategoriModalTitle").textContent =
        "Tambah Kategori";

    document.getElementById("kategoriModalDescription").textContent =
        "Tambahkan kategori barang baru.";

    modal.classList.add("active");

    document.getElementById("nama_kategori").focus();
}

function openEditKategoriModal(kategori) {
    const modal = document.getElementById("kategoriModal");
    const form = document.getElementById("kategoriForm");

    if (!modal || !form) {
        return;
    }

    document.getElementById("id_kategori").value =
        kategori.id_kategori || "";

    document.getElementById("nama_kategori").value =
        kategori.nama_kategori || "";

    document.getElementById("deskripsi").value =
        kategori.deskripsi || "";

    document.getElementById("kategoriModalTitle").textContent =
        "Edit Kategori";

    document.getElementById("kategoriModalDescription").textContent =
        "Perbarui informasi kategori.";

    modal.classList.add("active");

    document.getElementById("nama_kategori").focus();
}

function closeKategoriModal() {
    const modal = document.getElementById("kategoriModal");

    if (!modal) {
        return;
    }

    modal.classList.remove("active");
}

function confirmDeleteKategori(event, nama) {
    event.preventDefault();

    const form = event.target;

    Swal.fire({
        title: "Hapus kategori?",
        text: 'Kategori "' + nama + '" akan dihapus.',
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Batal",
        reverseButtons: true
    }).then(function (result) {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
}

document.addEventListener("click", function (event) {
    const modal = document.getElementById("kategoriModal");

    if (!modal) {
        return;
    }

    if (event.target === modal) {
        closeKategoriModal();
    }
});

document.addEventListener("keydown", function (event) {
    if (event.key === "Escape") {
        closeKategoriModal();
    }
});