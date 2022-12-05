import { ListGuesser,
    FieldGuesser, 
    InputGuesser, 
    CreateGuesser,
    ShowGuesser,
    EditGuesser, } from "@api-platform/admin";
import {  NumberField, EmailField, FunctionField, SelectArrayInput} from "react-admin";

const UserList = (props) => (
    <ListGuesser {...props}>
        <FieldGuesser source="nickname" label="Username" />
        <FieldGuesser source="lname" label="Last Name" />
        <FieldGuesser source="fname" label="First Name" />
      <EmailField source="email" />
      <FieldGuesser source="phone" />
      <FunctionField label="Roles" reference="roles" source="roles" render={
                record => 
                    `${record.roles.map(role => role).join(', ')}`
            } />
      <FieldGuesser source="adress" />
        <NumberField source="wallet" />
        <NumberField source="trees" />
    </ListGuesser>
  );
  const UserShow = (props) => (
      <ShowGuesser {...props}>
        <FieldGuesser source="nickname" label="Username" />
        <FieldGuesser source="lname" label="Last Name" />
        <FieldGuesser source="fname" label="First Name" />
      <EmailField source="email" />
      <FieldGuesser source="phone" />
      <FunctionField label="Roles" reference="roles" source="roles" render={
                record => 
                    `${record.roles.map(role => role).join(', ')}`
            } />
      <FieldGuesser source="adress" />
        <NumberField source="wallet" />
        <NumberField source="trees" />
    </ShowGuesser>
  );

  const UserCreate = (props) => (
    <CreateGuesser {...props}>
        <InputGuesser source="nickname" label="Username" />
        <InputGuesser source="lname" label="Last Name" />
        <InputGuesser source="fname" label="First Name" />
        <InputGuesser source="password" label="Password" />
      <InputGuesser source="email" />
      <InputGuesser source="phone" />
      <SelectArrayInput source="roles" choices={
         [
        {id:'ROLE_USER',name: 'User'},
        {id:'ROLE_ADMIN', name: 'Admin'},
      ]
      }/>
      <InputGuesser source="adress" />
        <InputGuesser source="wallet" />
        <InputGuesser source="trees" />
    </CreateGuesser>
  );
  const UserEdit = (props) => (
      <EditGuesser {...props}>
      <InputGuesser source="nickname" label="Username" />
      <InputGuesser source="lname" label="Last Name" />
      <InputGuesser source="fname" label="First Name" />
      {/* <InputGuesser source="password" label="Password" /> */}
    <InputGuesser source="email" />
    <InputGuesser source="phone" />
    <SelectArrayInput source="roles" choices={
      [
        {id:'ROLE_USER',name: 'User'},
        {id:'ROLE_ADMIN', name: 'Admin'},
      ]
    }/>
    <InputGuesser source="adress" />
      <InputGuesser source="wallet" />
      <InputGuesser source="trees" />
  </EditGuesser>
  );

  function User(){
    return {
        list: UserList,
        show: UserShow,
        create: UserCreate,
        edit: UserEdit,
    }
  }

  export default User;