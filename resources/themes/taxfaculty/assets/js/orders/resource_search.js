Vue.component('resource-search', {
    props:{
        resourcerecordes : {
            required: true,
            default: [],
        },
        categories: {
            required: true,
        },
        webinars: {
            required: true,
        },
        faqs: {
            required: true,
        },
        articles: {
            required: true,
        },
        events: {
            required: true,
        },
        acts: {
            required: true,
        },
        courses: {
            required: true,
        },
        tickets: {
            required: true,
        },
    },
    data: function () {
        return{
            filterArray: [],
            filterCategoryArray:[],
            filterSubCategoryArray: [],
            filterCategoryShow: true,
            filterCategoryName : "",
            sortByName: "",
            totalcountforarticles: 0,
            totalcountforwebinars: 0,
            totalcountforfaqs: 0,
            totalcountforevents: 0,
            totalcountforcourse: 0,
        }
    },


    methods: {
        countTotalFIlter(){
            this.categories.forEach((category) => {
                if(category.type == 'webinars'){
                    category.category.forEach((cat) =>{
                        this.totalcountforwebinars += cat.count;
                    });
                }
                if(category.type == 'articles'){
                    category.category.forEach((cat) =>{
                        this.totalcountforarticles += cat.count;
                    });
                }
                if(category.type == 'faqs'){
                    category.category.forEach((cat) =>{
                        this.totalcountforfaqs += cat.count;
                    });
                }
                if(category.type == 'events'){
                    category.category.forEach((cat) =>{
                        this.totalcountforevents += cat.count;
                    });
                }
                if(category.type == 'courses'){
                    category.category.forEach((cat) =>{
                        this.totalcountforcourse += cat.count;
                    });
                }
            })
        },
        filterContent(filtername){
            this.filterArray = [];
            this.filterCategoryArray = [];
            this.filterSubCategoryArray = [];
            this.resourcerecordes.forEach((record) =>{                
                if(record.search_type == filtername){
                    if(!(this.filterArray.find(filterdata => filterdata.id  == record.id))){
                        this.filterArray.push(record);
                    }
                }            
            });
            this.categories.forEach((record) => {
                if(record.type == filtername){
                    this.filterCategoryArray.push(record);
                }
                if(record.sub_category && record.sub_category.length > 0 && record.type == filtername){
                    this.filterSubCategoryArray.push(record);
                }
            });
            this.sortByDefault();
        },
        filterByCategory(filtername, filterParent){
            
            this.filterArray = [];
            this.filterSubCategoryArray = [];

            let subCategoriesSlugs = [];
            
            this.categories.forEach((record) => {
                if(record.type == filterParent){

                    let currentCategory = record.category.find(cat => cat.slug == filtername);
                    record.sub_category.forEach((sub) => {
                        if(sub.parent_category_id == currentCategory.category_id) {
                            subCategoriesSlugs.push(sub.slug);
                        }
                    });
                }
            });
            // console.log(subCategoriesSlugs);
            
            this.resourcerecordes.forEach((records) => {
                search_type = records.search_type;
                
                if(records.categories && search_type == filterParent ){
    
                    if(search_type == 'webinars'){
                        if(records.categories.slug == filtername || subCategoriesSlugs.includes(records.categories.slug)){
                            if(!(this.filterArray.find(filterdata => filterdata.id == records.id ))) {
                                this.filterArray.push(records);
                            }
                        }
                    } else {
                        // alert('hello');
                        if(records.categories && records.categories.length > 0 ) {
                            records.categories.forEach((record) => {
                                
                                if(record.slug == filtername || subCategoriesSlugs.includes(record.slug)){
                                    
                                    // if(records.id != 30){
                                        // console.log(records); 
                                        if(!(this.filterArray.find(filterdata => filterdata.id == records.id ))) {
                                            // console.log(records);
                                            this.filterArray.push(records);
                                            // console.log(this.filterArray);
                                        }  
                                    // }
                                    
                                }
                            });
                        }
                    }
                }
            });
            this.categories.forEach((record) => {
                // console.log(record.sub_category)
                if(record.sub_category && record.sub_category.length > 0 && record.type == filterParent){
                    let currentCategory = record.category.find(cat => cat.slug == filtername);
                    let filteredSubCategory = record.sub_category.filter((cat) => cat.parent_category_id == currentCategory.category_id)
                    let subCatgory = {
                        "category" : record.category,
                        "sub_category" : filteredSubCategory,
                        "type" : record.type
                    }
                    this.filterSubCategoryArray.push(subCatgory);
                }
            });
            this.sortByDefault();
        },
        filterBySubCategory(filtername, filterParent){
            this.filterArray = [];
            this.resourcerecordes.forEach((records) => {
                search_type = records.search_type;
                if(records.categories && search_type == filterParent ){
                    
                    if(search_type == 'webinars'){
                        if(records.categories.slug == filtername){
                            if(!(this.filterArray.find(filterdata => filterdata.id == records.id ))) {
                                this.filterArray.push(records);
                            }
                        }
                    } else {
                        if(records.categories && records.categories.length > 0 ) {
                            records.categories.forEach((record) => {
                                if(record.slug == filtername ){
                                    if(!(this.filterArray.find(filterdata => filterdata.id == records.id ))) {
                                        this.filterArray.push(records);
                                    }
                                }
                            });
                        }
                    }
                }
            });
            this.sortByDefault();
        },
        sortByRelevanceAndDate(event){
            this.sortByName = event.target.value;
                if(this.sortByName == "relevance"){
                    function compare(a, b) {
                        if (a.relevance < b.relevance)
                          return -1;
                        if (a.relevance > b.relevance)
                          return 1;
                        return 0;
                      }
                  
                      this.filterArray.sort(compare);
                    return this.filterArray.reverse();
                }
                if(this.sortByName == "last_updated"){
                    function compare(a, b) {
                    if (a.updated_at < b.updated_at)
                        return -1;
                    if (a.updated_at > b.updated_at)
                        return 1;
                    return 0;
                    }
                
                    this.filterArray.sort(compare);
                    return this.filterArray.reverse();
                }
        },
        sortByDefault(){
            if(this.sortByName == "relevance"){
                function compare(a, b) {
                    if (a.relevance < b.relevance)
                      return -1;
                    if (a.relevance > b.relevance)
                      return 1;
                    return 0;
                  }
              
                  this.filterArray.sort(compare);
                return this.filterArray.reverse();
            }
            if(this.sortByName == "last_updated"){
                function compare(a, b) {
                if (a.updated_at < b.updated_at)
                    return -1;
                if (a.updated_at > b.updated_at)
                    return 1;
                return 0;
                }
            
                this.filterArray.sort(compare);
                return this.filterArray.reverse();
            }
        },
        redirectFunction(item){
            if(item.is_redirect){
                window.location.href = item.redirect_url;
            }
            else{
                window.location.href = "/events/show/"+item.slug;
            }
        },
        saperateSubCategory(){
            this.categories.forEach((records) => {
                if(records.sub_category && records.sub_category.length > 0) {
                    this.filterSubCategoryArray.push(records.sub_category);
                    // this.filterSubCategoryArray.push(records);
                }
                    
            });
            // console.log(this.filterSubCategoryArray)
        },
    },

    created(){
        this.countTotalFIlter();
        this.filterArray = this.resourcerecordes;
        this.filterCategoryArray = this.categories;
        this.filterSubCategoryArray = this.categories;
        this.saperateSubCategory();
    }
})
