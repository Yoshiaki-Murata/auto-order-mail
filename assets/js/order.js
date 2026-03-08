document.addEventListener("DOMContentLoaded", () => {
    const body = document.getElementById("orderBody")
    const addBtn = document.getElementById("addRow")

    // ボタンをしたら部品選択行をを追加する
    addBtn.addEventListener("click", () => {
        const row = body.querySelector("tr").cloneNode(true)
        row.querySelector(".price").textContent = 0
        row.querySelector(".rowTotal").textContent = 0
        row.querySelector(".qty").value = 1
        row.querySelector(".partSelect").selectedIndex = 0
        body.appendChild(row)
    })

    // 部品が選択されたら
    body.addEventListener("change", e => {
        if (e.target.classList.contains("partSelect")) {
            updateRow(e.target.closest("tr"))
        }
    })

    // 個数が押されたら
    body.addEventListener("input", e => {
        if (e.target.classList.contains("qty")) {
            updateRow(e.target.closest("tr"))
        }
    })

    // 削除ボタンを押したら押された行を削除する。
    body.addEventListener("click", e => {
        if (e.target.classList.contains("removeRow")) {
            if (body.rows.length > 1) {
                e.target.closest("tr").remove()
                updateTotal()
            }
        }
    })

    // 各行の情報描画する関数
    function updateRow(row) {
        const select = row.querySelector(".partSelect")
        //選択された部品の値段を取得
        const price = select.options[select.selectedIndex].dataset.price || 0
        //個数を取得
        const qty = row.querySelector(".qty").value
        // 取得したpriceをNum型に変換してtextに描画する。
        row.querySelector(".price").textContent = Number(price).toLocaleString()
        // 単価と個数を計算
        const total = price * qty
        // 計算したtotalを合計金額に描画する
        row.querySelector(".rowTotal").textContent = total.toLocaleString()
        updateTotal()
    }

    // 総合計金額を計算して描画する関数
    function updateTotal() {
        // 初期化
        let sum = 0
        // 総合計金額をforeachで計算する
        document.querySelectorAll(".rowTotal").forEach(el => {
            sum += Number(el.textContent.replace(/,/g, ''))
        })
        // 描画する
        document.getElementById("grandTotal").textContent = sum.toLocaleString()
    }
})
