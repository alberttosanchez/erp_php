<script>
    
    cleanPagination = () => {
        let elements = document.querySelectorAll('#pagination_items > li');
        for (let i = 0; i < elements.length; i++) {
            elements[i].remove();                
        }
    }

    renderPagination = () =>
    {
        
        if (null !== this.state.pagination.counter || this.state.pagination.counter !== ""){
    
            var numberOfItems = [];
            for (let i = 0; i < this.state.pagination.pages; i++)
            {
                numberOfItems[i] = (i+1).toString();            
            }  
    
            
            numberOfItems.map( (keyNumber) => {
                
                if ( 
                    keyNumber.toString() === this.state.pagination.first_page.toString()   ||
                    keyNumber.toString() === this.state.pagination.prev_page.toString()    ||
                    keyNumber.toString() === this.state.pagination.current_page.toString() ||
                    keyNumber.toString() === this.state.pagination.next_page.toString()    ||
                    keyNumber.toString() === this.state.pagination.last_page.toString()
                    )
                    {                        

                        let li = document.createElement('li');
                            li.setAttribute('key',keyNumber);
                            li.setAttribute('style','padding:0 10px;');
                        
                        let button = document.createElement('button');
                            button.setAttribute('name','selected_page');
                            button.setAttribute('class', (this.state.pagination.current_page.toString() === keyNumber.toString() ? "btn_pagination cursor" : "btn_pagination") );
                            button.setAttribute('onclick','paginationClick(this)');
                            button.setAttribute('value',keyNumber);
                            button.innerHTML = keyNumber;
                            button.disabled = (this.state.pagination.current_page.toString() === keyNumber.toString() ? true : false);
                            setTimeout(() => {
                                li.appendChild(button);                                
                            }, 300);
    
                        let pagination = document.querySelector('#pagination_items');
                            pagination.appendChild(li);
                            //pagination = pagination.lastElementChild;
                            //pagination.parentNode.insertBefore(li,pagination)
                            
                       /*  return (                
                            <li key={keyNumber}><button
                                name="selected_page"
                                class={`btn_pagination ${(props.onPagination.current_page.toString() === keyNumber.toString() ) ? "cursor" : ""}`} 
                                onClick={props.onPaginationClick} 
                                value={keyNumber}
                                disabled={ (props.onPagination.current_page.toString() === keyNumber.toString() ) ? true : false }
                            >
                                {keyNumber}
                            </button></li>                                    
                        ); */
                    }
                    return [];
            });
    
            //class={`table_pagination ${ (props.onPagination.counter === "") ? "no-show" : ""}`}
    
            let table_pagination = document.querySelector('.table_pagination');
    
            if ( this.state.pagination.counter === "" && table_pagination.className === "table_pagination")
            {
                table_pagination.setAttribute('class','table_pagination no-show');
            }
            else
            {
                table_pagination.setAttribute('class','table_pagination');
            }
           
            let left_button = document.querySelector('#pagination > button:first-child');
                left_button.setAttribute('class', (this.state.pagination.prev_page === "" ? "no-show" : "") );
                left_button.value = this.state.pagination.prev_page;    
            
            let right_button = document.querySelector('#pagination button:last-child');
                right_button.setAttribute('class', (this.state.pagination.next_page === "" ? "no-show" : "") );
                right_button.value = this.state.pagination.next_page;    
        }
    }
    
    paginationClick = (e) =>{

        this.state = {
            ...this.state,
            pagination : {
                ...this.state.pagination,
                [e.name] : e.value
            }
        }

        setTimeout(() => {
            this.cleanUsersTable();
            this.cleanPagination();
            this.getUsersInfo();
        }, 300);
    }

</script>
  
<div class="table_pagination no-show">
    <ul id="pagination" class="pagination unstyled-list">                            
        <button 
            type="button"             
            onclick="paginationClick(this)"             
            name="selected_page"
            >
                &laquo;
            </button>                            
            <span 
                id="pagination_items" 
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
            onclick="paginationClick(this)"            
            name="selected_page"
        >
            &raquo;
        </button>                            
    </ul>    
</div>
   