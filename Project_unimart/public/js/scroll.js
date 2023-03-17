//Scroll back-top Lấy id theo button
const button = document.getElementById("myBtn");

// Khi người dùng cuộn xuống 20px từ đầu trang, hãy hiển thị nút
window.onscroll = () => scrollFunction();
function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        button.style.display = "block";
    } else {
        button.style.display = "none";
    }
}

// Khi người dùng click vào nút, cuộn lên đầu trang
function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}
