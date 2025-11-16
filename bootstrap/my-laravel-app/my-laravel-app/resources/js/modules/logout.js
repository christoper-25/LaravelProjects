// resources/js/modules/logout.js
import Swal from "sweetalert2";

document.addEventListener("click", e => {
  const btn = e.target.closest(".logout-btn");
  if (!btn) return;

  e.preventDefault();
  const form = btn.closest("form");
  if (!form) return;

  Swal.fire({
    title: "Are you sure?",
    text: "You will be logged out.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#3085d6",
    confirmButtonText: "Yes, log out",
    background: "#111",
    color: "#fff",
  }).then(result => {
    if (result.isConfirmed) form.submit();
  });
});
