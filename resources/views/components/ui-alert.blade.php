<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
window.Alert = {

    success(message, title = 'Berhasil') {
        Swal.fire({
            icon: 'success',
            title: title,
            text: message,
            confirmButtonText: 'OK'
        });
    },

    error(message, title = 'Error') {
        Swal.fire({
            icon: 'error',
            title: title,
            text: message,
            confirmButtonText: 'OK'
        });
    },

    warning(message, title = 'Peringatan') {
        Swal.fire({
            icon: 'warning',
            title: title,
            text: message,
            confirmButtonText: 'OK'
        });
    },

    info(message, title = 'Informasi') {
        Swal.fire({
            icon: 'info',
            title: title,
            text: message,
            confirmButtonText: 'OK'
        });
    },

    notify(message, icon = 'success', position = 'top-end') {
        Swal.fire({
            toast: true,
            position: position,
            icon: icon,
            title: message,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    },

    plain(message, duration = 2000) {
        Swal.fire({
            text: message,
            showConfirmButton: false,
            timer: duration
        });
    },

    confirm(options = {}) {

        return Swal.fire({

            icon: options.icon || 'question',

            title: options.title || 'Konfirmasi',

            text: options.text || 'Apakah Anda yakin?',

            showCancelButton: true,

            confirmButtonText: options.confirmText || 'Ya',

            cancelButtonText: options.cancelText || 'Tidak',

            confirmButtonColor: options.confirmColor || '#198754',

            cancelButtonColor: options.cancelColor || '#dc3545',

            reverseButtons: true
        });

    },

    loading(message = 'Memproses...') {

        Swal.fire({

            title: message,

            allowOutsideClick: false,

            allowEscapeKey: false,

            didOpen: () => {

                Swal.showLoading();

            }
        });
    },

    close() {
        Swal.close();
    }
};
</script>

<script>

window.hapusChecklist = function (form) {

    Swal.fire({

        title: 'Hapus checklist?',

        text: 'Data yang dihapus tidak dapat dikembalikan.',

        icon: 'warning',

        showCancelButton: true,

        confirmButtonColor: '#dc3545',

        cancelButtonColor: '#6c757d',

        confirmButtonText: 'Ya, hapus',

        cancelButtonText: 'Batal'

    }).then((result) => {

        if (result.isConfirmed) {

            form.submit();

        }

    });

};

</script>

@if(session('success'))
<script>
    Alert.notify("{{ session('success') }}", 'success');
</script>
@endif

@if(session('error'))
<script>
    Alert.notify("{{ session('error') }}", 'error');
</script>
@endif

@if(session('warning'))
<script>
    Alert.notify("{{ session('warning') }}", 'warning');
</script>
@endif

@if(session('info'))
<script>
    Alert.notify("{{ session('info') }}", 'info');
</script>
@endif




<!-- dialog yes / no

<button onclick="hapusData()">
    Hapus
</button>

<script>

function hapusData() {

    Alert.confirm({

        title: 'Hapus Data',

        text: 'Data akan dihapus permanen',

        icon: 'warning',

        confirmText: 'Ya, hapus',

        cancelText: 'Batal'

    }).then((result) => {

        if (result.isConfirmed) {

            document.getElementById('formDelete').submit();

        }

    });

}

</script>


2. Dialog tanpa header

Alert.plain('Data berhasil diperbarui');


3. Notify kanan atas

Alert.toast({

    icon: 'success',

    title: 'Berhasil',

    text: 'Data disimpan'

});

4. Notify error

Alert.toast({

    icon: 'error',

    title: 'Gagal',

    text: 'Terjadi kesalahan'

});

5. Alert tema info

Alert.dialog({

    icon: 'info',

    title: 'Informasi',

    text: 'Versi aplikasi 1.0.0'

});

Alert.confirm({

    title: 'Keluar?',

    text: 'Simpan perubahan terlebih dahulu',

    confirmText: 'Simpan',

    cancelText: 'Batal',

    confirmColor: '#0d6efd',

    cancelColor: '#6c757d'

});

return redirect()
    ->back()
    ->with('notify', [
        'type' => 'success',
        'title' => 'Berhasil',
        'text' => 'Data berhasil disimpan'
    ]);

    @if(session('notify'))

<script>

Alert.toast({

    icon: '{{ session("notify.type") }}',

    title: '{{ session("notify.title") }}',

    text: '{{ session("notify.text") }}'

});

</script>

@endif -->