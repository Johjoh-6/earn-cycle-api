import { ListGuesser,
    FieldGuesser, 
    ResourceGuesser, 
    InputGuesser, 
    CreateGuesser,
    ShowGuesser,
    EditGuesser, } from "@api-platform/admin";
import { TextField , ReferenceField, ReferenceInput, AutocompleteInput, ChipField, EmailField} from "react-admin";

const Rubbish = (props) => (
    <ListGuesser {...props}>
      <ReferenceField label="category" source="category" reference="categories">
        <ChipField  source="name" />
      </ReferenceField>
  
      <FieldGuesser source="nbStreet" />
      <FieldGuesser source="streetName" />
      <FieldGuesser source="city" />
      <FieldGuesser source="country" />
      <FieldGuesser source="postalCode" />
      <FieldGuesser source="latitude" />
      <FieldGuesser source="longitude" />
      {props.createdBy ? 
      <ReferenceField label="createdBy" source="createdBy" reference="users">
        <EmailField  source="email" />
      </ReferenceField> : 
       <TextField  label="createdBy" emptyText="API" textAlign="center"/>
      }
      
      <FieldGuesser source="deleted" textAlign="center"/>
    </ListGuesser>
  );
  const RubbishShow = props => (
    <ShowGuesser {...props}>
      <ReferenceField label="category" source="category" reference="categories">
        <ChipField  source="name" />
      </ReferenceField>
      <FieldGuesser source="nbStreet" />
      <FieldGuesser source="streetName" />
      <FieldGuesser source="city" />
      <FieldGuesser source="country" />
      <FieldGuesser source="postalCode" />
      <FieldGuesser source="latitude" />
      <FieldGuesser source="longitude" />
      {props.createdBy ? 
      <ReferenceField label="createdBy" source="createdBy" reference="users">
        <EmailField  source="email" />
      </ReferenceField> : 
       <TextField  label="createdBy" emptyText="API" textAlign="center"/>
      }
      
      <FieldGuesser source="deleted" textAlign="center"/>
    </ShowGuesser>
  );
  const RubbishCreate = props => (
    <CreateGuesser {...props}>
      <ReferenceInput
        source="category"
        reference="categories"
      >
        <AutocompleteInput
          filterToQuery={searchText => ({ name: searchText })}
          optionValue="name"
          optionText="name"
          label="Category"
        />
      </ReferenceInput>
  
      <InputGuesser source="nbStreet" />
      <InputGuesser source="streetName" />
      <InputGuesser source="city" />
      <InputGuesser source="country" />
      <InputGuesser source="postalCode" />
      <InputGuesser source="latitude" />
      <InputGuesser source="longitude" />
      <InputGuesser source="deleted" />
    </CreateGuesser>
  );
  const RubbishEdit = props => (
    <EditGuesser {...props}>
      <ReferenceInput
        source="name"
        reference="categories"
      >
        <AutocompleteInput
          filterToQuery={searchText => ({ category: searchText })}
          optionText="name"
          label="category"
        />
      </ReferenceInput>
  
      <InputGuesser source="nbStreet" />
      <InputGuesser source="streetName" />
      <InputGuesser source="city" />
      <InputGuesser source="country" />
      <InputGuesser source="postalCode" />
      <InputGuesser source="latitude" />
      <InputGuesser source="longitude" />
      <InputGuesser source="deleted" />
    </EditGuesser>
  );

  function Rubbishes (){
    return {
    list: Rubbish,
    show: RubbishShow,
    create: RubbishCreate,
    edit: RubbishEdit,
    }
  };
  export default Rubbishes;