console.log("QRCode script loaded successfully");

document.addEventListener("DOMContentLoaded", function () {
    const QRCodeScannerModal = document.getElementById("qrcode-scanner-modal");
    let html5QrcodeScanner = null;
    let isScanning = false;

    function stopScanner() {
        if (html5QrcodeScanner && isScanning) {
            html5QrcodeScanner.clear().then(() => {
                html5QrcodeScanner = null;
                isScanning = false;
                document.getElementById("reader").innerHTML = "";
            }).catch((err) => {
                console.error("Error clearing scanner:", err);
            });
        }
    }

    // Fungsi sukses & gagal PASTIKAN ADA!
    function onScanSuccess(code) {
        console.log("Scanned:", code);
        stopScanner();

        const isEnter = QRCodeScannerModal.dataset.isEnter === "1";
        const url = isEnter ? enterPresenceUrl : outPresenceUrl;

        handlePresence(url, code);

        bootstrap.Modal.getInstance(QRCodeScannerModal)?.hide();
        setTimeout(() => window.location.reload(), 1000);
    }

    function onScanFailure(error) {
        // silently ignore errors
    }

    QRCodeScannerModal.addEventListener("shown.bs.modal", (event) => {
        console.log("Modal opened");

        QRCodeScannerModal.dataset.isEnter = event.relatedTarget?.dataset.isEnter ?? "1";

        setTimeout(() => {
            console.log("Delay selesai, mencoba render scanner...");

            const readerElement = document.getElementById("reader");
            readerElement.innerHTML = "";

            html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: { width: 250, height: 250 } },
                false
            );

            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
            isScanning = true;
        }, 800);
    });

    QRCodeScannerModal.addEventListener("hidden.bs.modal", () => {
        stopScanner();
    });

    // Tambahkan handlePresence juga
    async function handlePresence(url, code) {
        try {
            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: new URLSearchParams({ code })
            });

            const result = await response.json();
            if (result.success) {
                alert("Berhasil: " + result.message);
            } else {
                alert("Gagal: " + result.message);
            }
        } catch (e) {
            alert("Terjadi kesalahan saat mengirim data");
            console.error(e);
        }
    }
});
