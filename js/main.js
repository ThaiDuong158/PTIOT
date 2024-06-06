const $ = document.querySelector.bind(document)
const $$ = document.querySelectorAll.bind(document)

const header = $('.header')
const content = $('.content')
const footer = $('.footer')
const sidebarHides = $$('.sidebar-hide')
const sidebarWidths = $$('.sidebar-width')
const sidebarBtn = $('.sidebar-mini')
const sidebarItems = $$('.sidebar-item')
const dropdownList = $('.dropdown-list')

let contentDefaultHeight = content.clientHeight

function updateContentHeight() {
    // const totalHeight = footer.clientHeight;
    const contentHeight = window.innerHeight - header.clientHeight - footer.clientHeight;
    if (contentDefaultHeight < contentHeight) {
        content.style.height = contentHeight + "px";
    }
}

updateContentHeight()
window.addEventListener('resize', updateContentHeight);

let sidebarHide = () => {
    sidebarHides.forEach(sidebarHide => {
        sidebarHide.classList.toggle("sidebar-small-hide");
    });
    sidebarWidths.forEach(sidebarWidth => {
        sidebarWidth.classList.toggle("sidebar-small-width");
        sidebarWidth.classList.add("sidebar-transition");
    });
}

sidebarBtn.addEventListener('click', sidebarHide)

document.addEventListener('DOMContentLoaded', function () {
    // Lấy đường dẫn URL hiện tại
    const currentURL = window.location.pathname;

    // Duyệt qua tất cả các thẻ <a> trong sidebar
    sidebarItems.forEach(item => {
        // Lấy href của thẻ <a>
        const href = new URL(item.href, window.location.origin).pathname;

        // So sánh đường dẫn hiện tại với href của thẻ <a>
        if (currentURL === href) {
            // Thêm lớp 'left-line' vào thẻ <a> tương ứng
            item.classList.add('left-line');
        }
    });
    dropdownList.style.right = `calc(100% - ${$('.header__login').clientWidth}px)`;
});
