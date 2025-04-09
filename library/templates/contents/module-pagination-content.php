<script>
    
    cleanModulesTable = () => {
        let elements = document.querySelectorAll('#mod_pagination tbody > tr');
        for (let i = 0; i < elements.length; i++) {
            elements[i].remove();                
        }
    }

    cleanModulePagination = () => {
        let elements = document.querySelectorAll('#mod_pagination_items > li');
        for (let i = 0; i < elements.length; i++) {
            elements[i].remove();                
        }
    }

    renderModulePagination = () =>
    {
        
        if (null !== this.state.mod_pagination.counter || this.state.mod_pagination.counter !== ""){
    
            var numberOfItems = [];
            for (let i = 0; i < this.state.mod_pagination.pages; i++)
            {
                numberOfItems[i] = (i+1).toString();            
            }  
    
            
            numberOfItems.map( (keyNumber) => {
                
                if ( 
                    keyNumber.toString() === this.state.mod_pagination.first_page.toString()   ||
                    keyNumber.toString() === this.state.mod_pagination.prev_page.toString()    ||
                    keyNumber.toString() === this.state.mod_pagination.current_page.toString() ||
                    keyNumber.toString() === this.state.mod_pagination.next_page.toString()    ||
                    keyNumber.toString() === this.state.mod_pagination.last_page.toString()
                    )
                    {                        

                        let li = document.createElement('li');
                            li.setAttribute('key',keyNumber);
                            li.setAttribute('style','padding:0 10px;');
                        
                        let button = document.createElement('button');
                            button.setAttribute('name','selected_page');
                            button.setAttribute('class', (this.state.mod_pagination.current_page.toString() === keyNumber.toString() ? "btn_pagination cursor" : "btn_pagination") );
                            button.setAttribute('onclick','ModulePaginationClick(this)');
                            button.setAttribute('value',keyNumber);
                            button.innerHTML = keyNumber;
                            button.disabled = (this.state.mod_pagination.current_page.toString() === keyNumber.toString() ? true : false);
                            setTimeout(() => {
                                li.appendChild(button);                                
                            }, 300);
    
                        let mod_pagination = document.querySelector('#mod_pagination_items');
                            mod_pagination.appendChild(li);
                            
                    }
                    return [];
            });
    
            let table_mod_pagination = document.querySelector('.table_mod_pagination');
    
            if ( this.state.mod_pagination.counter === "" && table_mod_pagination.className === "table_mod_pagination" && this.state.modules.length < 1 )
            {
                table_mod_pagination.setAttribute('class','table_mod_pagination no-show');
            }
            else if ( this.state.mod_pagination.counter !== "" && table_mod_pagination.className === "table_mod_pagination no-show" && this.state.modules.length > 0 )
            {
                table_mod_pagination.setAttribute('class','table_mod_pagination');
            }
           
            let left_button = document.querySelector('#mod_pagination > button:first-child');
                left_button.setAttribute('class', (this.state.mod_pagination.prev_page === "" ? "no-show" : "") );
                left_button.value = this.state.mod_pagination.prev_page;    
            
            let right_button = document.querySelector('#mod_pagination button:last-child');
                right_button.setAttribute('class', (this.state.mod_pagination.next_page === "" ? "no-show" : "") );
                right_button.value = this.state.mod_pagination.next_page;    
        }
    }
    
    ModulePaginationClick = (e) =>{

        this.state = {
            ...this.state,
            mod_pagination : {
                ...this.state.mod_pagination,
                [e.name] : e.value
            }
        }

        setTimeout(() => {
            this.cleanModulesTable();
            this.cleanModulePagination();
            this.getModules();
        }, 300);
    }

</script>
  
<div class="table_mod_pagination no-show">
    <ul id="mod_pagination" class="mod_pagination unstyled-list">                            
        <button 
            type="button"             
            onclick="ModulePaginationClick(this)"             
            name="selected_page"
            >
                &laquo;
            </button>                            
            <span 
                id="mod_pagination_items" 
                style="
                    display: flex;
                    justify-content: space-between;
                    padding: 0px 20px;
                "
            >
                <?php // pagination ?>
            </span>
        <button 
            type="button"             
            onclick="ModulePaginationClick(this)"
            
            name="selected_page"
            >
                &raquo;
            </button>                            
    </ul>    
</div>
   