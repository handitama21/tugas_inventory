let keranjang = [];

function formatRupiah(angka) {
    return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0
    }).format(angka);
}

function tambahKeKeranjang(button) {
    const card = button.closest(".produk-card");

    const idBarang = Number(card.dataset.id);
    const nama = card.dataset.nama;
    const harga = Number(card.dataset.harga);
    const stok = Number(card.dataset.stok);

    const item = keranjang.find(
        item => item.id_barang === idBarang
    );

    if (item) {
        if (item.jumlah >= stok) {
            Swal.fire({
                title: "Stok Maksimal",
                text: "Jumlah barang sudah mencapai stok yang tersedia.",
                icon: "warning",
                confirmButtonText: "OK"
            });

            return;
        }

        item.jumlah++;
    } else {
        keranjang.push({
            id_barang: idBarang,
            nama: nama,
            harga: harga,
            stok: stok,
            jumlah: 1
        });
    }

    renderKeranjang();
}

function tambahJumlah(index) {
    const item = keranjang[index];

    if (!item) {
        return;
    }

    if (item.jumlah >= item.stok) {
        Swal.fire({
            title: "Stok Maksimal",
            text: "Jumlah barang sudah mencapai stok yang tersedia.",
            icon: "warning",
            confirmButtonText: "OK"
        });

        return;
    }

    item.jumlah++;

    renderKeranjang();
}

function kurangiJumlah(index) {
    const item = keranjang[index];

    if (!item) {
        return;
    }

    item.jumlah--;

    if (item.jumlah <= 0) {
        keranjang.splice(index, 1);
    }

    renderKeranjang();
}

function hapusItem(index) {
    keranjang.splice(index, 1);

    renderKeranjang();
}

function hitungDiskon(item) {
    if (item.jumlah >= 10) {
        return item.harga;
    }

    return 0;
}

function renderKeranjang() {
    const container = document.getElementById("keranjangList");
    const jumlahItem = document.getElementById("jumlahItem");

    if (keranjang.length === 0) {
        container.innerHTML = `
            <div class="keranjang-kosong">

                <div class="empty-icon">
                    🛒
                </div>

                <h3>Keranjang masih kosong</h3>

                <p>
                    Tambahkan barang dari daftar barang.
                </p>

            </div>
        `;

        jumlahItem.textContent = "0";

        updateTotal();

        return;
    }

    let totalJumlah = 0;

    container.innerHTML = "";

    keranjang.forEach((item, index) => {

        totalJumlah += item.jumlah;

        const subtotalNormal =
            item.harga * item.jumlah;

        const diskon =
            hitungDiskon(item);

        const subtotal =
            subtotalNormal - diskon;

        container.innerHTML += `
            <div class="keranjang-item">

                <div class="item-info">

                    <h4>
                        ${escapeHtml(item.nama)}
                    </h4>

                    <p>
                        ${formatRupiah(item.harga)}
                        ×
                        ${item.jumlah}
                    </p>

                    ${
                        diskon > 0
                            ? `
                                <p style="color:#16a34a;">
                                    Diskon
                                    ${formatRupiah(diskon)}
                                </p>
                            `
                            : ""
                    }

                </div>

                <div class="item-total">

                    <strong>
                        ${formatRupiah(subtotal)}
                    </strong>

                    <div class="qty-control">

                        <button
                            type="button"
                            onclick="kurangiJumlah(${index})"
                        >
                            −
                        </button>

                        <span>
                            ${item.jumlah}
                        </span>

                        <button
                            type="button"
                            onclick="tambahJumlah(${index})"
                        >
                            +
                        </button>

                    </div>

                    <button
                        type="button"
                        class="item-remove"
                        onclick="hapusItem(${index})"
                    >
                        Hapus
                    </button>

                </div>

            </div>
        `;
    });

    jumlahItem.textContent = totalJumlah;

    updateTotal();
}

function updateTotal() {
    let subtotal = 0;
    let totalDiskon = 0;

    keranjang.forEach(item => {

        subtotal +=
            item.harga * item.jumlah;

        totalDiskon +=
            hitungDiskon(item);
    });

    const grandTotal =
        subtotal - totalDiskon;

    document.getElementById("subtotal").textContent =
        formatRupiah(subtotal);

    document.getElementById("diskon").textContent =
        "-" + formatRupiah(totalDiskon);

    document.getElementById("grandTotal").textContent =
        formatRupiah(grandTotal);

    hitungPembayaran();
}

function hitungPembayaran() {
    const uangBayar =
        Number(
            document.getElementById("uangBayar").value
        ) || 0;

    let subtotal = 0;
    let totalDiskon = 0;

    keranjang.forEach(item => {

        subtotal +=
            item.harga * item.jumlah;

        totalDiskon +=
            hitungDiskon(item);
    });

    const grandTotal =
        subtotal - totalDiskon;

    const hasilPembayaran =
        document.getElementById("hasilPembayaran");

    const kembalian =
        document.getElementById("kembalian");

    if (uangBayar >= grandTotal) {

        const hasil =
            uangBayar - grandTotal;

        hasilPembayaran.classList.remove("kurang");

        kembalian.textContent =
            formatRupiah(hasil);

    } else {

        const kurang =
            grandTotal - uangBayar;

        hasilPembayaran.classList.add("kurang");

        kembalian.textContent =
            "-" + formatRupiah(kurang);
    }
}

function resetKeranjang() {
    if (keranjang.length === 0) {
        return;
    }

    Swal.fire({
        title: "Kosongkan Keranjang?",
        text: "Semua barang yang sudah dipilih akan dihapus.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, kosongkan",
        cancelButtonText: "Batal",
        reverseButtons: true
    }).then((result) => {

        if (result.isConfirmed) {

            keranjang = [];

            document.getElementById("uangBayar").value = "";

            renderKeranjang();

            Swal.fire({
                title: "Keranjang Dikosongkan",
                text: "Semua barang berhasil dihapus.",
                icon: "success",
                confirmButtonText: "OK"
            });
        }
    });
}

async function selesaikanTransaksi() {

    if (keranjang.length === 0) {

        Swal.fire({
            title: "Keranjang Kosong",
            text: "Tambahkan barang terlebih dahulu.",
            icon: "warning",
            confirmButtonText: "OK"
        });

        return;
    }

    const uangBayar =
        Number(
            document.getElementById("uangBayar").value
        ) || 0;

    let subtotal = 0;
    let totalDiskon = 0;

    keranjang.forEach(item => {

        subtotal +=
            item.harga * item.jumlah;

        totalDiskon +=
            hitungDiskon(item);
    });

    const grandTotal =
        subtotal - totalDiskon;

    if (uangBayar <= 0) {

        Swal.fire({
            title: "Uang Bayar Belum Diisi",
            text: "Masukkan jumlah uang yang dibayarkan pembeli.",
            icon: "warning",
            confirmButtonText: "OK"
        });

        document.getElementById("uangBayar").focus();

        return;
    }

    if (uangBayar < grandTotal) {

        const kurang =
            grandTotal - uangBayar;

        Swal.fire({
            title: "Uang Kurang",
            html: `
                <p>Total transaksi:</p>

                <strong style="font-size:20px;">
                    ${formatRupiah(grandTotal)}
                </strong>

                <p style="margin-top:15px;">
                    Uang pembeli:
                </p>

                <strong>
                    ${formatRupiah(uangBayar)}
                </strong>

                <div style="
                    margin-top:18px;
                    padding:12px;
                    background:#fef2f2;
                    color:#dc2626;
                    border-radius:8px;
                    font-weight:bold;
                ">
                    Kekurangan:
                    -${formatRupiah(kurang)}
                </div>
            `,
            icon: "warning",
            confirmButtonText: "OK"
        });

        return;
    }

    const kembalian =
        uangBayar - grandTotal;

    const hasilKonfirmasi =
        await Swal.fire({
            title: "Transaksi Siap Diproses",

            html: `
                <div style="
                    text-align:left;
                    margin-top:15px;
                ">

                    <div style="
                        display:flex;
                        justify-content:space-between;
                        margin-bottom:10px;
                    ">
                        <span>Total</span>

                        <strong>
                            ${formatRupiah(grandTotal)}
                        </strong>
                    </div>

                    <div style="
                        display:flex;
                        justify-content:space-between;
                        margin-bottom:10px;
                    ">
                        <span>Uang Bayar</span>

                        <strong>
                            ${formatRupiah(uangBayar)}
                        </strong>
                    </div>

                    <div style="
                        margin-top:15px;
                        padding:14px;
                        background:#ecfdf5;
                        color:#15803d;
                        border-radius:8px;
                        display:flex;
                        justify-content:space-between;
                    ">
                        <strong>Kembalian</strong>

                        <strong>
                            ${formatRupiah(kembalian)}
                        </strong>
                    </div>

                </div>
            `,

            icon: "success",

            showCancelButton: true,

            confirmButtonText: "Proses Transaksi",

            cancelButtonText: "Batal",

            reverseButtons: true
        });

    if (!hasilKonfirmasi.isConfirmed) {
        return;
    }

    const btnTransaksi =
        document.getElementById("btnTransaksi");

    btnTransaksi.disabled = true;
    btnTransaksi.textContent = "Memproses...";

    const dataTransaksi = {
        nama_pembeli:
            document.getElementById("namaPembeli").value.trim(),

        uang_bayar:
            uangBayar,

        keranjang:
            keranjang.map(item => ({
                id_barang: item.id_barang,
                jumlah: item.jumlah
            }))
    };

    try {

        const response = await fetch(
            "index.php?page=transaksi&action=simpan",
            {
                method: "POST",

                headers: {
                    "Content-Type": "application/json"
                },

                body: JSON.stringify(dataTransaksi)
            }
        );

        const result =
            await response.json();

        if (!result.success) {

            throw new Error(
                result.message ||
                "Transaksi gagal diproses."
            );
        }

        await Swal.fire({
            title: "Transaksi Berhasil!",
            html: `
                <p>Transaksi berhasil disimpan.</p>

                <strong style="font-size:20px;">
                    ${result.kode_transaksi}
                </strong>

                <p style="margin-top:15px;">
                    Kembalian:
                </p>

                <strong style="
                    font-size:22px;
                    color:#15803d;
                ">
                    ${formatRupiah(result.kembalian)}
                </strong>
            `,
            icon: "success",
            confirmButtonText: "Selesai"
        });

        window.location.href =
            "index.php?page=transaksi";

    } catch (error) {

        Swal.fire({
            title: "Transaksi Gagal",
            text: error.message,
            icon: "error",
            confirmButtonText: "OK"
        });

        btnTransaksi.disabled = false;
        btnTransaksi.textContent =
            "Selesaikan Transaksi";
    }
}

function escapeHtml(text) {
    const div = document.createElement("div");

    div.textContent = text;

    return div.innerHTML;
}

document.addEventListener("DOMContentLoaded", () => {

    const searchInput =
        document.getElementById("searchBarang");

    if (searchInput) {

        searchInput.addEventListener(
            "input",
            function () {

                const keyword =
                    this.value.toLowerCase().trim();

                const cards =
                    document.querySelectorAll(
                        ".produk-card"
                    );

                cards.forEach(card => {

                    const nama =
                        card.dataset.nama
                            .toLowerCase();

                    const id =
                        card.dataset.id;

                    const cocok =
                        nama.includes(keyword) ||
                        id.includes(keyword);

                    card.style.display =
                        cocok ? "" : "none";
                });
            }
        );
    }

    const tanggal =
        document.getElementById("tanggalTransaksi");

    if (tanggal) {

        const sekarang = new Date();

        tanggal.textContent =
            sekarang.toLocaleDateString(
                "id-ID",
                {
                    weekday: "long",
                    day: "numeric",
                    month: "long",
                    year: "numeric"
                }
            );
    }

    renderKeranjang();
});