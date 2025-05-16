function toggleEditProfile(edit) {
    document.getElementById('editProfileCard').style.display = edit ? 'block' : 'none';
    document.getElementById('editProfileBtn').style.display = edit ? 'none' : 'inline-block';
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

function togglePasswordSection(show) {
    document.getElementById('passwordSection').style.display = show ? 'block' : 'none';
    document.getElementById('showPasswordSectionBtn').style.display = show ? 'none' : 'inline-block';
    if (!show) {
        document.getElementById('current_password').value = '';
        document.getElementById('password').value = '';
        document.getElementById('password_confirmation').value = '';
    }
}

function showVerifyError() {
    // window.verifyUrl must be set in Blade!
    let alertHtml = `<div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
        You must verify your email address before you can edit your profile.
        Verify <a href="${window.verifyUrl}">here</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>`;
    let btn = document.getElementById('editProfileBtn');
    btn.insertAdjacentHTML('afterend', alertHtml);
    setTimeout(function () {
        let alert = btn.parentNode.querySelector('.alert');
        if (alert) {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 300);
        }
    }, 3000);
}

document.addEventListener('DOMContentLoaded', function () {
    // These variables must be set in Blade!
    if (window.editProfileShowErrors) {
        document.getElementById('editProfileCard').style.display = 'block';
        document.getElementById('editProfileBtn').style.display = 'none';
        setTimeout(function () {
            window.scrollTo({
                top: document.body.scrollHeight,
                behavior: 'smooth'
            });
        }, 200);
    }
    if (window.editProfileShowPasswordSection) {
        document.getElementById('passwordSection').style.display = 'block';
        var showBtn = document.getElementById('showPasswordSectionBtn');
        if (showBtn) showBtn.style.display = 'none';
    }

    // Overlay on hover
    const label = document.querySelector('label[for="profile_picture"]');
    const img = document.getElementById('profilePicturePreview');
    window.previewProfilePicture = function (input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                img.src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    };

    // Auto-close the alert after 3 seconds
    document.querySelectorAll('.alert[role="alert"]').forEach(function (alert) {
        setTimeout(function () {
            alert.classList.remove('show');
            setTimeout(() => alert.remove(), 300);
        }, 3000);
    });
});
