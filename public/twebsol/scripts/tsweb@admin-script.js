function sAlert(icon, title, html, options = {}) {
  return Swal.fire({
    title: title,
    html: html,
    icon: icon,
    ...options,
  });
}
