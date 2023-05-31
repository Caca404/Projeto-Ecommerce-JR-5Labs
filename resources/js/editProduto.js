window.removeImg = function(index){
    var buttonToRemove = document.querySelector('[data-bs-slide-to="'+index+'"]');
    var carouselItemToRemove = document.querySelector('[data-item="'+index+'"]');

    let imagePath = carouselItemToRemove.querySelector('img').getAttribute('src');
    let imagesToRemove = JSON.parse(document.querySelector('[name="imagesToRemove[]"]').value);
    imagesToRemove.push(imagePath);
    document.querySelector('[name="imagesToRemove[]"]').value = JSON.stringify(imagesToRemove);

    buttonToRemove.parentElement.remove();
    carouselItemToRemove.remove();

    if(document.querySelector('.carousel-inner').childElementCount == 1)
        document.querySelector('#hasNotImage').classList.add('active');

    else document.querySelector('.carousel-item:not(#hasNotImage)').classList.add('active');
}