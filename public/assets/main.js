
const menu_icon = document.getElementById('menu_icon');
const modal_menu = document.getElementById('modal_menu');
const modal_menu_content = document.getElementById('modal_menu_content');
menu_icon.addEventListener('click', function (e) {
    modal_menu.classList.remove('invisible');
    modal_menu.classList.add('visible');
    modal_menu_content.classList.remove('-translate-x-full')

    modal_menu_content.classList.add('translate-0')

});
document.addEventListener('click', function (e) {
    if (!e.target.closest('#modal_menu_content') && e.target !== menu_icon || e.target.id === "modal_menu_icon") {
        modal_menu.classList.remove('visible');
        modal_menu.classList.add('invisible');
        modal_menu_content.classList.remove('translate-0')
        modal_menu_content.classList.add('-translate-x-full')
    }
});
const list_library_items = document.querySelectorAll('.drop-down');
for (const listLibraryItem of list_library_items) {
    listLibraryItem.addEventListener('click', function (e) {
        if (!listLibraryItem.classList.contains('active')) {
            delete_class_if_exists(list_library_items, ['bg-[#80D3C3]', 'active']);
            listLibraryItem.classList.toggle('bg-[#80D3C3]');
            listLibraryItem.classList.toggle('active');
        } else {
            delete_class_if_exists(list_library_items, ['bg-[#80D3C3]', 'active']);
        }

    })
}

function delete_class_if_exists(items, name_class) {
    for (const item of items) {
        if (Array.isArray(name_class)) {
            for (const name of name_class) {
                item.classList.remove(name)
            }
        }
    }
}

function closeModal() {
    modal_menu.classList.remove('visible');
    modal_menu.classList.add('invisible');
    modal_menu_content.classList.remove('translate-0')
    modal_menu_content.classList.add('-translate-x-full')
}


// Xu ly report
const reportRadios = document.querySelectorAll('input[name="report_radio"]');
const reportOtherInput = document.getElementById('report_other_input');
// Bắt sự kiện khi radio button thay đổi
reportRadios.forEach(function (radioButton) {
    radioButton.addEventListener('change', function () {
        if (radioButton.checked && radioButton.id === 'report_radio_other') {
            reportOtherInput.classList.remove('hidden');
        } else {
            reportOtherInput.classList.add('hidden');
        }
    });
});

// Xu ly document share
const inputField = document.getElementById('document_share');
const message = document.getElementById('document_share_message');

inputField.addEventListener('click', async e => {

    // const text = event.target.innerText
    await navigator.clipboard.writeText(inputField.value);  // nã text vô clipboard
    message.textContent = 'Copied!!' // thêm tí thông báo copy thành công

    inputField.addEventListener('click', async e => {
        if (!navigator.clipboard) return // méo có clipboard, abort!

        try {
            await navigator.clipboard.writeText(inputField.value);
            message.textContent = 'Copied to clipboard'
        } catch (error) {
            console.error('Failed to copy!', error)
            // handle error ở đây nếu có gì sai sai, thường chỉ xảy ra khi copy cái gì đó lớn vô clipboard
        }
    })
})


// modal report
function showModalReport(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('hidden');
    modal.classList.add('fixed');
    modal.classList.add('translate-y-full');
    setTimeout(function () {
        modal.classList.remove('translate-y-full');
    }, 0);
}

function hideModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('translate-y-full');
    setTimeout(function () {
        modal.classList.add('hidden');
        modal.classList.remove('fixed');
        modal.classList.remove('translate-y-full');
    }, 500);
}

// Xử lý sự kiện khi click vào nút đóng modal
const closeButtons = document.querySelectorAll('[data-modal-hide]');
closeButtons.forEach(function (button) {
    const targetModalId = button.getAttribute('data-modal-hide');
    button.addEventListener('click', function () {
        hideModal(targetModalId);
    });
});
