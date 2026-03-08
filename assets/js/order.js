// document.addEventListener("DOMContentLoaded", () => {
//     const body = document.getElementById("orderBody")
//     const addBtn = document.getElementById("addRow")
//     addBtn.addEventListener("click", () => {
//         const row = body.querySelector("tr").cloneNode(true)
//         row.querySelector(".price").textContent = 0
//         row.querySelector(".rowTotal").textContent = 0
//         row.querySelector(".qty").value = 1
//         row.querySelector(".partSelect").selectedIndex = 0
//         body.appendChild(row)
//     })
//     body.addEventListener("change", e => {
//         if (e.target.classList.contains("partSelect")) {
//             updateRow(e.target.closest("tr"))
//         }
//     })
//     body.addEventListener("input", e => {
//         if (e.target.classList.contains("qty")) {
//             updateRow(e.target.closest("tr"))
//         }
//     })
//     body.addEventListener("click", e => {
//         if (e.target.classList.contains("removeRow")) {
//             if (body.rows.length > 1) {
//                 e.target.closest("tr").remove()
//                 updateTotal()
//             }
//         }
//     })
//     function updateRow(row) {
//         const select = row.querySelector(".partSelect")
//         const price = select.options[select.selectedIndex].dataset.price || 0
//         const qty = row.querySelector(".qty").value
//         row.querySelector(".price").textContent = Number(price).toLocaleString()
//         const total = price * qty
//         row.querySelector(".rowTotal").textContent = total.toLocaleString()
//         updateTotal()
//     }
//     function updateTotal() {
//         let sum = 0
//         document.querySelectorAll(".rowTotal").forEach(el => {
//             sum += Number(el.textContent.replace(/,/g, ''))
//         })
//         document.getElementById("grandTotal").textContent = sum.toLocaleString()
//     }
// })