import { useState } from "react";
import { Route } from "react-router-dom";
import { CustomRoutes } from "react-admin";
import { 
  HydraAdmin, 
  fetchHydra as baseFetchHydra, 
  hydraDataProvider as baseHydraDataProvider, 
  ListGuesser, 
  FieldGuesser, 
  ResourceGuesser, 
  InputGuesser, 
  CreateGuesser, 
  useIntrospection,
  hydraSchemaAnalyzer} from "@api-platform/admin";
import { parseHydraDocumentation } from "@api-platform/api-doc-parser";
import { TextField , ReferenceField, ReferenceInput, AutocompleteInput} from "react-admin";
import Login from "./login";
import authProvider from "./authProvider";

import Rubbishes from "./tables/Rubbish";
import User from "./tables/User";
import Category from "./tables/Category";
import Partner from "./tables/Partner";
import Voucher from "./tables/Voucher";
import UserVoucher from "./tables/UserVoucher";

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
  
    if (sessionStorage.getItem("auth-token")) {
      introspect();
      return <></>;
    }
    return <Login />;
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
  
      // Prevent infinite loop if the auth-token is expired
      sessionStorage.removeItem("auth-token");
  
      setRedirectToLogin(true);
  
      return {
        api,
        response,
        status,
      };
    }
  };
const schemaAnalyzer = hydraSchemaAnalyzer();
const dataProvider = (setRedirectToLogin) => baseHydraDataProvider({
    entrypoint: entrypoint,
    httpClient: fetchHydra,
    apiDocumentationParser: apiDocumentationParser(setRedirectToLogin),
    useEmbedded: false,
    mercure: true,
  });


const AdminPage = () => {
  const [redirectToLogin, setRedirectToLogin] = useState(false);
  return (
    <HydraAdmin
        dataProvider={dataProvider(setRedirectToLogin)}
        entrypoint={window.origin}
        authProvider={authProvider}
        schemaAnalyzer={schemaAnalyzer}
    >
          <CustomRoutes>
          {redirectToLogin ? <Route path="/" element={<RedirectToLogin />} /> : null}
        </CustomRoutes>
        <ResourceGuesser name="categories" list={Category().list} show={Category().show} />
        <ResourceGuesser name="rubbishes" list={Rubbishes().list} show={Rubbishes().show} create={Rubbishes().create} edit={Rubbishes().edit} />
        <ResourceGuesser name="users" list={User().list} show={User().show} create={User().create} edit={User().edit} />
        <ResourceGuesser name="partners" list={Partner().list} />
        <ResourceGuesser name="vouchers" list={Voucher().list} show={Voucher().show} create={Voucher().create} edit={Voucher().edit} />
        <ResourceGuesser name="user_vouchers" list={UserVoucher().list} show={UserVoucher().show} create={UserVoucher().create} edit={UserVoucher().edit} />
    </HydraAdmin>
)};

export default AdminPage;