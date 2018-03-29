import { fetchConfigProps } from '../common/config';

const uri = 'api/v1/courses/';

/**
 *
 * @param {string} label
 * @return {Promise}
 * */
function findByLabel(label) {
  const config = fetchConfigProps();

  return fetch(`${config.baseUrl}${uri}${label}`)
    .then(res => res.json());
}

const CourseApi = {
  findByLabel,
};

export default CourseApi;

