
const appId = "a4be47e8";
const appKey = "2f9e577ce67239d4cb6de514d03212d6";
const baseUrl = `https://api.edamam.com/api/recipes/v2?type=public&app_id=${appId}&app_key=${appKey}`;
const recipeContainer=document.querySelector("#recipe-container");
const txtSearch=document.querySelector("#txtSearch");
const btnFind=document.querySelector(".btn");
const loadingEle=document.querySelector("#loading");
const calorieRangeSelect = document.querySelector("#calorieRange");

btnFind.addEventListener("click", ()=>loadRecipies(txtSearch.value, calorieRangeSelect.value));

txtSearch.addEventListener("keyup", (e) => {
  const inputVal = txtSearch.value; 
  if(e.keyCode === 13) {
    loadRecipies(inputVal);
  }
});

const toggleLoad = (element, isShow) => {
  element.classList.toggle("hide", isShow)

}
const setScrollPosition = () => {
  recipeContainer.scrollTo({ top: 0, behavior: "smooth" });
}

function loadRecipies(type="paneer", calorieRange="0-100000") {
  toggleLoad(loadingEle, false);
  const url=baseUrl+`&q=${type}`;
  fetch(url)
    .then(res => res.json())
    .then((data => {
      const [minCalories, maxCalories] = calorieRange.split('-').map(Number);
      const filteredRecipes = data.hits.filter(hit => {
        const calories = hit.recipe.calories;
        return calories >= minCalories && calories <= maxCalories;
      });
      renderRecipies(filteredRecipes);
      toggleLoad(loadingEle, true);
    }))
    .catch(error => {
      console.log("Error loading recipes:", error);  
      toggleLoad(loadingEle, true);
    })
    .finally(() => setScrollPosition());
}

loadRecipies();

const getRecipeStepsStr=(ingredientLines = []) => {
  let str = " ";
  for (var step of ingredientLines) {
    str=str+`<li> ${step}</li>`
  }
  return str;
}

const renderRecipies = (recipeList = []) => {
  recipeContainer.innerHTML = "";
  recipeList.forEach(recipeObj=> {
    const {label:recipeTitle, ingredientLines, image:recipeImage, calories } = recipeObj.recipe;
    const formattedCalories = `Calories: ${Math.round(calories)} kcal`;
    const recipeStepStr =`<li>${formattedCalories}</li>` + getRecipeStepsStr(ingredientLines);
    const htmlStr = `<div class="recipe">
      <div class="recipe-title">${recipeTitle}</div>
      <div class="recipe-image">
        <img src="${recipeImage}">
      </div>
      <div class="recipe-text">
        <ul>
          ${recipeStepStr}
        </ul>
      </div>
    </div>`;
    recipeContainer.insertAdjacentHTML("beforeend", htmlStr);
  });
}