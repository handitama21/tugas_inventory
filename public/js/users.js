document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.getElementById("searchUser");
    const usersTable = document.getElementById("usersTable");

    if (searchInput && usersTable) {

        searchInput.addEventListener("input", function () {

            const keyword = this.value.toLowerCase().trim();
            const rows = usersTable.querySelectorAll("tbody tr");

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

function openAddModal() {

    const modal = document.getElementById("userModal");
    const form = document.getElementById("userForm");

    if (!modal || !form) {
        return;
    }

    form.reset();

    document.getElementById("id_user").value = "";

    document.getElementById("modalTitle").textContent = "Tambah Pengguna";

    document.getElementById("modalDescription").textContent =
        "Tambahkan pengguna baru ke sistem.";

    document.getElementById("password").required = true;

    document.getElementById("password").value = "";

    document.getElementById("passwordHelp").textContent =
        "Wajib diisi saat menambah pengguna.";

    modal.classList.add("active");

    document.getElementById("nip").focus();
}

function openEditModal(user) {

    const modal = document.getElementById("userModal");
    const form = document.getElementById("userForm");

    if (!modal || !form) {
        return;
    }

    document.getElementById("id_user").value = user.id_user || "";
    document.getElementById("nip").value = user.nip || "";
    document.getElementById("nama").value = user.nama || "";
    document.getElementById("email").value = user.email || "";
    document.getElementById("role").value = user.role || "user";
    document.getElementById("status").value = user.status || "aktif";

    document.getElementById("password").value = "";
    document.getElementById("password").required = false;

    document.getElementById("modalTitle").textContent = "Edit Pengguna";

    document.getElementById("modalDescription").textContent =
        "Perbarui informasi pengguna.";

    document.getElementById("passwordHelp").textContent =
        "Kosongkan jika password tidak ingin diubah.";

    modal.classList.add("active");

    document.getElementById("nama").focus();
}

function closeUserModal() {

    const modal = document.getElementById("userModal");

    if (!modal) {
        return;
    }

    modal.classList.remove("active");
}

function confirmDelete(event, nama) {
    event.preventDefault();

    const form = event.target;

    Swal.fire({
        title: "Hapus pengguna?",
        text: "Pengguna \"" + nama + "\" akan dihapus secara permanen.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Hapus",
        cancelButtonText: "Batal",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });

    return false;
}

document.addEventListener("click", function (event) {

    const modal = document.getElementById("userModal");

    if (!modal) {
        return;
    }

    if (event.target === modal) {
        closeUserModal();
    }

});

document.addEventListener("keydown", function (event) {

    if (event.key === "Escape") {
        closeUserModal();
    }

});