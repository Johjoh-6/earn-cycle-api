import { HydraAdmin, fetchHydra, hydraDataProvider,  FieldGuesser,
  ListGuesser,
  ResourceGuesser } from "@api-platform/admin";
import { parseHydraDocumentation } from "@api-platform/api-doc-parser";
import { TextField } from "react-admin";

const entrypoint = process.env.REACT_APP_API_ENTRYPOINT;

const dataProvider = hydraDataProvider({
    entrypoint,
    httpClient: fetchHydra,
    apiDocumentationParser: parseHydraDocumentation,
    mercure: true,
    useEmbedded: false,
});

// const CategoryList = (props) => (
// <ListGuesser {...props}>
//     <FieldGuesser source="name" />
//     {/* Use react-admin components directly when you want complex fields. */}
//     <TextField label="rubbish" source="category.rubbishList" />
//   </ListGuesser>
// );

export default () => (
  <HydraAdmin
      dataProvider={dataProvider}
      entrypoint={entrypoint}
  />
);
{/* <ResourceGuesser
    name="categories"
    list={CategoryList}
  />
</HydraAdmin> */}