import { ListGuesser,
    FieldGuesser, 
    ShowGuesser} from "@api-platform/admin";
import { TextField , ReferenceField, ReferenceArrayField, Datagrid, EmailField, Pagination, ShowButton} from "react-admin";


const CategoriesList = (props) => {
    return (
      <ListGuesser {...props}>
        <FieldGuesser source="name" />
        <FieldGuesser source="deleted" />
        {/* <FieldGuesser source="rubbishList" label="Rubbish Count" record={props.rubbishList.length}/> */}
      </ListGuesser>
    );
  };

  const CategoriesShow = props => (
    <ShowGuesser {...props}>
      <FieldGuesser source="name" />
      <ReferenceArrayField source="rubbishList" label="Rubbish list" reference="rubbishes" perPage={50} pagination={<Pagination />}>
                <Datagrid>
                    <TextField source="nbStreet" />
                    <TextField source="streetName" />
                    <TextField source="city" />
                    <TextField source="country" />
                    <TextField source="postalCode" />
                    <TextField source="latitude" />
                    <TextField source="longitude" />
                    {props.createdBy ? 
                    <ReferenceField label="createdBy" source="createdBy" reference="users">
                        <EmailField  source="email" />
                    </ReferenceField> : 
                    <TextField  label="createdBy" emptyText="API" textAlign="center"/>
                    }
                    <TextField source="deleted" textAlign="center"/>
                    <ShowButton />
                </Datagrid>
            </ReferenceArrayField>
      <FieldGuesser source="deleted" />
    </ShowGuesser>
  );

  function category(){
    return {
        list: CategoriesList,
        show: CategoriesShow,
    }
  } 

  export default category;