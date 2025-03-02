let slideIndex = 0;
showSlides();

function showSlides() {
  let i;
  // เลือกทั้งหมดที่มี class ชื่อ mySlides
  let slides = document.getElementsByClassName("mySlides");
  let dots = document.getElementsByClassName("dot");

  // ซ่อนทุกสไลด์
  for (i = 0; i < slides.length; i++) {
    slides[i].style.display = "none";
  }

  slideIndex++;
  // ถ้าถึงสไลด์สุดท้ายแล้วก็วนกลับมาสไลด์แรก
  if (slideIndex > slides.length) {slideIndex = 1}

  // ลบสถานะ active ของจุดทั้งหมด
  for (i = 0; i < dots.length; i++) {
    dots[i].className = dots[i].className.replace(" active", "");
  }

  // แสดงสไลด์ที่กำลังอยู่ในลำดับ และเพิ่ม active ให้กับจุดนั้น
  slides[slideIndex - 1].style.display = "block";
  dots[slideIndex - 1].className += " active";

  // ตั้งเวลาการแสดงสไลด์ถัดไป
  setTimeout(showSlides, 3000); // เปลี่ยนสไลด์ทุก ๆ 3 วินาที
}
