import { render } from 'enzyme';

import ViewCourse from '../index';

describe('<ViewCourse />', () => {
  it('should render initial-state properly', () => {
    const wrapper = render(<ViewCourse />);

    expect(wrapper).toMatchSnapshot();
  });
});
