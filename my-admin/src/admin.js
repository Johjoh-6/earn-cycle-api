import { useState } from "react";
import { Navigate, Route } from "react-router-dom";
import { CustomRoutes } from "react-admin";
import { 
  HydraAdmin, 
  fetchHydra as baseFetchHydra, 
  hydraDataProvider, 
  hydraSchemaAnalyzer, 
  ListGuesser, 
  FieldGuesser, 
  ResourceGuesser, 
  InputGuesser, 
  CreateGuesser, 
  useIntrospection} from "@api-platform/admin";
import { parseHydraDocumentation } from "@api-platform/api-doc-parser";
import { TextField , ReferenceField, ReferenceInput, AutocompleteInput} from "react-admin";

const entrypoint = process.env.REACT_APP_API_ENTRYPOINT;


const getHeaders = () => sessionStorage.getItem("auth-token") ? {
  Authorization: `Bearer ${sessionStorage.getItem("auth-token")}`,
} : {};
const fetchHydra = (url, options = {}) =>
  baseFetchHydra(url, {
    ...options,
    headers: getHeaders,
  });

  const RedirectToLogin = () => {
    const introspect = useIntrospection();
  
    if (localStorage.getItem("token")) {
      introspect();
      return <></>;
    }
    return <Navigate to="/login" />;
  };
const apiDocumentationParser = (setRedirectToLogin) => async () => {
    try {
      setRedirectToLogin(false);
  
      return await parseHydraDocumentation(entrypoint, { headers: getHeaders });
    } catch (result) {
      const { api, response, status } = result;
      if (status !== 401 || !response) {
        throw result;
      }
  
      // Prevent infinite loop if the token is expired
      localStorage.removeItem("token");
  
      setRedirectToLogin(true);
  
      return {
        api,
        response,
        status,
      };
    }
  };
const dataProvider = hydraDataProvider({
    entrypoint,
    docEntrypoint: entrypoint + '/index.html',
    httpClient: fetchHydra,
    apiDocumentationParser: parseHydraDocumentation,
    mercure: true,
    useEmbedded: false, 
});
const schemaAnalyzer = hydraSchemaAnalyzer();

const CategoriesList = (props) => {
  return (
    <ListGuesser {...props}>
      <FieldGuesser source="name" />
      <FieldGuesser source="deleted" />
      {/* <ReferenceField label="Rubbishid" source="rubbishList" reference="rubbishes">
        {props.rubbishList.length > 0 ? (
          props.rubbishList.map(rubbish => (
            <TextField key={rubbish.name} source={rubbish.name} />
          ))
        ) : <>0</>}
        <FieldGuesser source="id" />
      </ReferenceField> */}
    </ListGuesser>
  );
};

const Rubbish = (props) => (
  <ListGuesser {...props}>
    <ReferenceField label="category" source="category" reference="categories">
      <TextField  source="name" />
    </ReferenceField>

    <FieldGuesser source="nbStreet" />
    <FieldGuesser source="streetName" />
    <FieldGuesser source="city" />
    <FieldGuesser source="country" />
    <FieldGuesser source="postalCode" />
    <FieldGuesser source="latitude" />
    <FieldGuesser source="longitude" />
    <FieldGuesser source="deleted" />
  </ListGuesser>
);
const RubbishCreate = props => (
  <CreateGuesser {...props}>
    <InputGuesser source="categories" />
    <ReferenceInput
      source="name"
      reference="categories"
    >
      <AutocompleteInput
        filterToQuery={searchText => ({ category: searchText })}
        optionText="name"
        label="Type"
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
const UserList = (props) => (
  <ListGuesser {...props}>
    <FieldGuesser source="email" />
    <FieldGuesser source="phone" />
    <FieldGuesser source="roles" />
    <FieldGuesser source="deleted" />
  </ListGuesser>
);

const Admin = () => (
  <HydraAdmin
      schemaAnalyzer={schemaAnalyzer}
      dataProvider={dataProvider}
  >
    <ResourceGuesser
        name="categories"
        list={CategoriesList}
      />
    <ResourceGuesser
        name="rubbishes"
        list={Rubbish}
        create={RubbishCreate}
      />
    <ResourceGuesser
        name="users"
        list={UserList}
      />
    </HydraAdmin>
);

export default Admin;