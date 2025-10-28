document.querySelector('.payBtn').onclick = (event) =>
{
    if(document.querySelector('.payTitle').innerHTML == 'کل سبد خرید : 0')
    {
       alert('سبد خرید خالی است')
       
    }
    else
    {
        alert('با موفقیت پرداخت گردید :)')
        
    }
}